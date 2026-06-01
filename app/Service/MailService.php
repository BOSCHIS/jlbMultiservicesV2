<?php

require_once __DIR__ . '/../Entity/Contact.php';

class MailService
{
    private const NOM_SITE = 'JLB MULTISERVICES';
    private const EMAIL_EXPEDITEUR = 'no-reply@jlb-multiservices.fr';

    private string $emailDestinataire;
    private string $smtpHost = '127.0.0.1';
    private int $smtpPort = 1025;

    public function __construct()
    {
        $this->emailDestinataire = $this->getEnvValue('EMAIL_DESTINATAIRE');
    }

    public function sendContactEmail(Contact $contact): bool
    {
        if (empty($this->emailDestinataire)) {
            return false;
        }

        date_default_timezone_set('Europe/Paris');

        $boundary = 'boundary_' . md5(uniqid((string) time(), true));

        $subject = '[' . self::NOM_SITE . '] Nouvelle demande de devis de ' . $contact->name;

        $htmlContent = $this->buildHtmlContent($contact);
        $textContent = $this->buildTextContent($contact);

        $headers = [];
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-Type: multipart/alternative; boundary="' . $boundary . '"';
        $headers[] = 'From: ' . self::NOM_SITE . ' <' . self::EMAIL_EXPEDITEUR . '>';
        $headers[] = 'Reply-To: ' . $contact->email;
        $headers[] = 'X-Mailer: PHP/' . phpversion();

        $body = "--$boundary\r\n";
        $body .= "Content-Type: text/plain; charset=UTF-8\r\n";
        $body .= "Content-Transfer-Encoding: 8bit\r\n\r\n";
        $body .= $textContent . "\r\n\r\n";

        $body .= "--$boundary\r\n";
        $body .= "Content-Type: text/html; charset=UTF-8\r\n";
        $body .= "Content-Transfer-Encoding: 8bit\r\n\r\n";
        $body .= $htmlContent . "\r\n\r\n";

        $body .= "--$boundary--";

        $rawEmail = '';
        $rawEmail .= 'To: ' . $this->emailDestinataire . "\r\n";
        $rawEmail .= 'Subject: ' . $this->encodeSubject($subject) . "\r\n";
        $rawEmail .= implode("\r\n", $headers) . "\r\n\r\n";
        $rawEmail .= $body;

        return $this->sendWithSmtp($this->emailDestinataire, $rawEmail);
    }

    private function sendWithSmtp(string $to, string $rawEmail): bool
    {
        $socket = @fsockopen($this->smtpHost, $this->smtpPort, $errno, $errstr, 5);

        if (!$socket) {
            return false;
        }

        $this->readSmtp($socket);

        $this->writeSmtp($socket, "HELO localhost\r\n");
        $this->readSmtp($socket);

        $this->writeSmtp($socket, "MAIL FROM:<" . self::EMAIL_EXPEDITEUR . ">\r\n");
        $this->readSmtp($socket);

        $this->writeSmtp($socket, "RCPT TO:<" . $to . ">\r\n");
        $this->readSmtp($socket);

        $this->writeSmtp($socket, "DATA\r\n");
        $this->readSmtp($socket);

        $this->writeSmtp($socket, $rawEmail . "\r\n.\r\n");
        $this->readSmtp($socket);

        $this->writeSmtp($socket, "QUIT\r\n");

        fclose($socket);

        return true;
    }

    private function writeSmtp($socket, string $command): void
    {
        fwrite($socket, $command);
    }

    private function readSmtp($socket): string
    {
        $response = '';

        while ($line = fgets($socket, 515)) {
            $response .= $line;

            if (isset($line[3]) && $line[3] === ' ') {
                break;
            }
        }

        return $response;
    }

    private function buildHtmlContent(Contact $contact): string
    {
        $serviceRequested = !empty($contact->serviceRequested)
            ? htmlspecialchars($contact->serviceRequested)
            : 'Non précisée';

        $telephone = !empty($contact->telephone)
            ? htmlspecialchars($contact->telephone)
            : 'Non précisé';

        $address = !empty($contact->address)
            ? htmlspecialchars($contact->address)
            : 'Non précisée';

        return '
        <!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; background-color: #f4f4f4; margin: 0; padding: 0; }
                .container { max-width: 600px; margin: 20px auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
                .header { background: #319667; color: white; padding: 30px 20px; text-align: center; }
                .header h1 { margin: 0; font-size: 28px; }
                .content { padding: 30px 20px; }
                .field { margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px solid #eeeeee; }
                .field:last-child { border-bottom: none; }
                .label { font-weight: bold; color: #16593a; font-size: 18px; text-transform: uppercase; display: block; margin-bottom: 8px; }
                .value { font-size: 18px; color: #333; }
                .message-box { background-color: #f8f9fa; padding: 18px; border-left: 4px solid #16593a; border-radius: 4px; font-style: italic; }
                .footer { background-color: #f8f9fa; padding: 20px; text-align: center; font-size: 15px; color: #5c5c5c; }
                .footer p { margin: 5px 0; }
            </style>
        </head>

        <body>
            <div class="container">

                <div class="header">
                    <h1>📩 Nouvelle demande de devis 📩</h1>
                </div>

                <div class="content">

                    <div class="field">
                        <span class="label">👤 Nom complet</span>
                        <div class="value">' . htmlspecialchars($contact->name) . '</div>
                    </div>

                    <div class="field">
                        <span class="label">📧 Adresse email</span>
                        <div class="value">
                            <a href="mailto:' . htmlspecialchars($contact->email) . '">
                                ' . htmlspecialchars($contact->email) . '
                            </a>
                        </div>
                    </div>

                    <div class="field">
                        <span class="label">📞 Téléphone</span>
                        <div class="value">' . $telephone . '</div>
                    </div>

                    <div class="field">
                        <span class="label">📍 Adresse</span>
                        <div class="value">' . $address . '</div>
                    </div>

                    <div class="field">
                        <span class="label">🛠️ Prestation demandée</span>
                        <div class="value">' . $serviceRequested . '</div>
                    </div>

                    <div class="field">
                        <span class="label">💬 Message</span>
                        <div class="message-box">' . nl2br(htmlspecialchars($contact->message)) . '</div>
                    </div>

                    <div class="field">
                        <span class="label">🕐 Date de réception</span>
                        <div class="value">' . date('d/m/Y à H:i:s') . '</div>
                    </div>

                </div>

                <div class="footer">
                    <p><strong>' . self::NOM_SITE . '</strong></p>
                    <p>Message reçu via le formulaire de contact du site web</p>
                </div>

            </div>
        </body>
        </html>';
    }

    private function buildTextContent(Contact $contact): string
    {
        $text = "=== NOUVELLE DEMANDE DE DEVIS ===\n\n";
        $text .= "Nom : " . $contact->name . "\n";
        $text .= "Email : " . $contact->email . "\n";
        $text .= "Téléphone : " . (!empty($contact->telephone) ? $contact->telephone : 'Non précisé') . "\n";
        $text .= "Adresse : " . (!empty($contact->address) ? $contact->address : 'Non précisée') . "\n";
        $text .= "Prestation demandée : " . (!empty($contact->serviceRequested) ? $contact->serviceRequested : 'Non précisée') . "\n";
        $text .= "\nMessage :\n" . $contact->message . "\n\n";
        $text .= "Date : " . date('d/m/Y à H:i:s') . "\n";
        $text .= "Site : " . self::NOM_SITE;

        return $text;
    }

    private function encodeSubject(string $subject): string
    {
        return '=?UTF-8?B?' . base64_encode($subject) . '?=';
    }

    private function getEnvValue(string $key): string
    {
        $envPath = __DIR__ . '/../../.env';

        if (!file_exists($envPath)) {
            return '';
        }

        $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            $line = trim($line);

            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }

            if (!str_contains($line, '=')) {
                continue;
            }

            [$envKey, $value] = explode('=', $line, 2);

            if (trim($envKey) === $key) {
                return trim($value, " \t\n\r\0\x0B\"'");
            }
        }

        return '';
    }
}
