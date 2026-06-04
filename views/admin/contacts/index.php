<?php

$pageTitle = "Demandes de devis";

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= $pageTitle ?></title>
    <link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="/favicon.svg" />
    <link rel="shortcut icon" href="/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png" />
    <meta name="apple-mobile-web-app-title" content="MyWebSite" />
    <link rel="manifest" href="/site.webmanifest" />

    <link rel="stylesheet" href="/assets/style/admin.css">
</head>

<body>

    <main class="adminDashboard">

        <section class="adminCard contactsCard">

            <img
                src="/assets/media/JLB_Multiservices_logo.webp"
                alt="Logo"
                class="adminLogoSmall">

            <h1>Demandes de devis</h1>

            <a href="/admin/dashboard" class="backDashboard">
                Retour dashboard
            </a>

            <?php if (empty($contacts)) : ?>

                <p>Aucune demande enregistrée.</p>

            <?php else : ?>

                <table class="adminTable">

                    <thead>

                        <tr>
                            <th>Date</th>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Téléphone</th>
                            <th>Adresse</th>
                            <th>Prestation</th>
                            <th>Message</th>
                            <th>Action</th>
                        </tr>

                    </thead>

                    <tbody>

                        <?php foreach ($contacts as $contact) : ?>

                            <tr>

                                <td data-label="Date">
                                    <?= !empty($contact['sent_at'])
                                        ? date('d/m/Y H:i', strtotime($contact['sent_at']))
                                        : 'Non précisée' ?>
                                </td>

                                <td data-label="Nom">
                                    <?= htmlspecialchars($contact['name_contact']) ?>
                                </td>

                                <td data-label="Email">
                                    <?= htmlspecialchars($contact['email_contact']) ?>
                                </td>

                                <td data-label="Téléphone">
                                    <?= htmlspecialchars($contact['telephone_contact']) ?>
                                </td>

                                <td data-label="Adresse">
                                    <?= !empty($contact['address_contact'])
                                        ? htmlspecialchars($contact['address_contact'])
                                        : 'Non précisée' ?>
                                </td>

                                <td data-label="Prestation">
                                    <?= !empty($contact['service_requested'])
                                        ? htmlspecialchars($contact['service_requested'])
                                        : 'Non précisée' ?>
                                </td>

                                <td data-label="Message" class="messageCell">
                                    <?= nl2br(htmlspecialchars($contact['message_contact'])) ?>
                                </td>

                                <td data-label="Action">
                                    <form
                                        method="POST"
                                        action="/admin/contact/delete"
                                        class="deleteForm"
                                        onsubmit="return confirm('Supprimer cette demande ?')">

                                        <input
                                            type="hidden"
                                            name="id"
                                            value="<?= (int) $contact['id_contact'] ?>">

                                        <input
                                            type="hidden"
                                            name="csrf_token"
                                            value="<?= htmlspecialchars($_SESSION['admin_csrf_token'] ?? '') ?>">

                                        <button type="submit" class="deleteBtn">
                                            Supprimer
                                        </button>

                                    </form>
                                </td>

                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

            <?php endif; ?>

        </section>

    </main>

</body>

</html>