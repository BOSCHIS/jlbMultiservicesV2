<?php

$pageTitle = "Contact | JLB MULTISERVICES";
$pageDescription = "Contactez JLB MULTISERVICES pour un devis gratuit.";

$css = "/assets/style/contact.css";

require_once __DIR__ . '/../layout/header.php';

?>

<main>

    <article class="globalContact">

        <div>
            <h1>Contactez mon entreprise JLB MULTISERVICES</h1>

            <p>
                Remplissez le formulaire ci-dessous afin que nous puissions échanger sur vos besoins
                et vous proposer la solution la plus adaptée.
            </p>

            <p class="devisInfo">
                *Devis gratuit et sans engagement
            </p>
        </div>

        <div class="contactSection">

            <div class="infoContact">

                <ul>
                    <li>
                        <img
                            class="iconFooter"
                            src="/assets/media/marqueur.png"
                            alt="icône marqueur"
                            aria-label="icône d'un marqueur, accompagnant l'adresse postale de JLB MULTISERVICES">

                        <span>9 rue Antoine de Saint-Exupéry, 46090 PRADINES</span>
                    </li>

                    <li>
                        <img
                            class="iconFooter"
                            src="/assets/media/phone.png"
                            alt="icône téléphone"
                            aria-label="icône téléphone, accompagnant les coordonnées téléphoniques de l'entreprise JLB MULTISERVICES">

                        <span>06 49 46 24 98</span>
                    </li>
                </ul>

                <div class="map">
                    <p>Intervention sur l'ensemble du département du Lot</p>
                </div>

                <p id="textIntervention">
                    *Intervention sur l'ensemble du département du Lot
                </p>

            </div>

            <form id="contactForm" action="/contact/send" method="POST" novalidate>
                <input
                    type="hidden"
                    name="csrf_token"
                    value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">

                <div class="hiddenField">
                    <label>
                        Ne pas remplir ce champ
                        <input
                            type="text"
                            name="website"
                            tabindex="-1"
                            autocomplete="off">
                    </label>
                </div>

                <fieldset class="form">

                    <legend>Envoyer un message</legend>

                    <div class="messageSuccess close">

                        <span class="closeButtonSuccess">&times;</span>

                        <p id="successText">
                            Votre message a été envoyé avec succès &#x2713; <br>
                            Je prends connaissance de votre demande et reviens vers vous dans les plus brefs délais.
                        </p>

                    </div>

                    <div class="messageFail close">

                        <span class="closeButtonFail">&times;</span>

                        <p id="failText">
                            <strong>Erreur !</strong> Nous n'avons pas pu enregistrer votre message.
                        </p>

                    </div>

                    <?php if (!empty($selectedService)) : ?>

                        <div class="selectedServiceInfo">

                            <p>
                                Prestation demandée :
                                <strong><?= htmlspecialchars($selectedService['title']) ?></strong>
                            </p>

                        </div>

                        <input
                            type="hidden"
                            name="service_requested"
                            value="<?= htmlspecialchars($selectedService['title']) ?>">

                    <?php endif; ?>

                    <label>
                        <span class="labelTitle">
                            Nom <span class="required">*</span>
                        </span>

                        <input
                            required
                            aria-required="true"
                            type="text"
                            name="name"
                            minlength="2"
                            maxlength="40"
                            placeholder="Dupont Jean">
                    </label>

                    <label>
                        Adresse

                        <input
                            type="text"
                            name="address"
                            maxlength="90"
                            placeholder="12 rue des Lilas, 46000 Cahors">
                    </label>

                    <label>
                        Tél

                        <input
                            type="tel"
                            name="tel"
                            placeholder="06 12 24 48 96"
                            minlength="10"
                            maxlength="20">
                    </label>

                    <label>
                        <span class="labelTitle">
                            Email <span class="required">*</span>
                        </span>

                        <input
                            type="email"
                            name="email"
                            minlength="6"
                            maxlength="60"
                            placeholder="exemple@gmail.com">
                    </label>

                    <label>
                        <span class="labelTitle">
                            Message <span class="required">*</span>
                        </span>

                        <textarea
                            name="message"
                            rows="8"
                            cols="70"
                            minlength="10"
                            maxlength="1000"
                            placeholder="Bonjour,

Je vous contacte pour un devis de nettoyage de ma véranda de ma maison.

Merci par avance pour votre retour.

Cordialement."></textarea>
                    </label>

                    <p class="requiredFields">
                        <span class="required">*</span> <em>champs obligatoires</em>
                    </p>

                    <div class="checkboxCgu">

                        <input
                            type="checkbox"
                            id="cgu"
                            name="cgu"
                            required>

                        <label for="cgu">
                            J'ai pris connaissance de la
                            <a href="/politique-confidentialite" target="_blank">Politique de confidentialité</a>
                            et j'accepte le traitement de mes données personnelles
                        </label>

                    </div>

                    <button type="submit">
                        Envoyer
                    </button>

                </fieldset>

            </form>

        </div>

    </article>

</main>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>