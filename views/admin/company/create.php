<?php
$pageTitle = "Ajouter un bloc entreprise";
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

    <main class="adminLogin">

        <section class="adminCard">

            <img
                src="/assets/media/JLB_Multiservices_logo.webp"
                alt="Logo JLB MULTISERVICES"
                class="adminLogo">

            <h1>Ajouter un bloc entreprise</h1>

            <form
                method="POST"
                action="/admin/entreprise/create"
                class="adminForm"
                enctype="multipart/form-data">

                <label>
                    Titre

                    <input
                        type="text"
                        name="title"
                        placeholder="Exemple : Une entreprise locale"
                        required>
                </label>

                <label>
                    Contenu

                    <textarea
                        name="content"
                        rows="12"
                        placeholder="Rédigez le contenu de ce bloc."
                        required></textarea>
                </label>

                <label>
                    Ordre d'affichage

                    <input
                        type="number"
                        name="display_order"
                        min="1"
                        value="1"
                        required>
                </label>

                <label class="checkboxAdmin">
                    <input
                        type="checkbox"
                        name="is_active"
                        value="1"
                        checked>

                    Afficher ce bloc sur la page entreprise
                </label>

                <label>
                    Image du bloc

                    <input
                        type="file"
                        name="image"
                        accept="image/png, image/jpeg, image/webp">
                </label>

                <button type="submit" class="adminButton">
                    Ajouter
                </button>

                <a href="/admin/entreprise" class="backDashboard">
                    Retour à la page entreprise
                </a>

            </form>

        </section>

    </main>

</body>

</html>