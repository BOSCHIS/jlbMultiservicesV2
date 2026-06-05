<?php
$pageTitle = "Ajouter une réalisation avant/après";
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?></title>
    <link rel="stylesheet" href="/assets/style/admin.css">
</head>

<body>

    <main class="adminLogin">

        <section class="adminCard">

            <img
                src="/assets/media/JLB_Multiservices_logo.webp"
                alt="Logo JLB MULTISERVICES"
                class="adminLogo">

            <h1>Ajouter une réalisation avant/après</h1>

            <?php if (!empty($_SESSION['admin_error'])) : ?>

                <div class="adminAlertError">
                    <?= htmlspecialchars($_SESSION['admin_error']) ?>
                </div>

                <?php unset($_SESSION['admin_error']); ?>

            <?php endif; ?>

            <form
                method="POST"
                action="/admin/realisations/create"
                class="adminForm"
                enctype="multipart/form-data">

                <label>
                    Titre

                    <input
                        type="text"
                        name="title"
                        placeholder="Exemple : Nettoyage de terrasse"
                        required>
                </label>

                <label>
                    Image avant

                    <input
                        type="file"
                        name="image_before"
                        accept="image/png, image/jpeg, image/webp"
                        required>
                </label>

                <label>
                    Image après

                    <input
                        type="file"
                        name="image_after"
                        accept="image/png, image/jpeg, image/webp"
                        required>
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

                    Afficher cette réalisation sur l’accueil
                </label>

                <button type="submit" class="adminButton">
                    Ajouter
                </button>

                <a href="/admin/realisations" class="backDashboard">
                    Retour aux réalisations
                </a>

            </form>

        </section>

    </main>

</body>

</html>