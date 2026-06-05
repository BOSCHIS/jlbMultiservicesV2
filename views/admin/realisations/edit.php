<?php

$pageTitle = "Modifier une réalisation avant/après";

$realisation = $realisation ?? null;

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

            <h1>Modifier une réalisation avant/après</h1>

            <?php if (!$realisation) : ?>

                <p>Réalisation introuvable.</p>

                <a href="/admin/realisations" class="backDashboard">
                    Retour aux réalisations
                </a>

            <?php else : ?>

                <?php if (!empty($_SESSION['admin_error'])) : ?>

                    <div class="adminAlertError">
                        <?= htmlspecialchars($_SESSION['admin_error']) ?>
                    </div>

                    <?php unset($_SESSION['admin_error']); ?>

                <?php endif; ?>

                <form
                    method="POST"
                    action="/admin/realisations/edit?id=<?= (int) $realisation['id_realisation'] ?>"
                    class="adminForm"
                    enctype="multipart/form-data">

                    <label>
                        Titre

                        <input
                            type="text"
                            name="title"
                            value="<?= htmlspecialchars($realisation['title']) ?>"
                            required>
                    </label>

                    <div class="currentImageBox">

                        <p>Image avant actuelle :</p>

                        <img
                            src="/assets/uploads/realisations/<?= rawurlencode($realisation['image_before']) ?>"
                            alt="Avant <?= htmlspecialchars($realisation['title']) ?>"
                            class="adminServiceImage">

                    </div>

                    <label>
                        Nouvelle image avant

                        <input
                            type="file"
                            name="image_before"
                            accept="image/png, image/jpeg, image/webp">
                    </label>

                    <div class="currentImageBox">

                        <p>Image après actuelle :</p>

                        <img
                            src="/assets/uploads/realisations/<?= rawurlencode($realisation['image_after']) ?>"
                            alt="Après <?= htmlspecialchars($realisation['title']) ?>"
                            class="adminServiceImage">

                    </div>

                    <label>
                        Nouvelle image après

                        <input
                            type="file"
                            name="image_after"
                            accept="image/png, image/jpeg, image/webp">
                    </label>

                    <label>
                        Ordre d'affichage

                        <input
                            type="number"
                            name="display_order"
                            min="1"
                            value="<?= (int) $realisation['display_order'] ?>"
                            required>
                    </label>

                    <label class="checkboxAdmin">
                        <input
                            type="checkbox"
                            name="is_active"
                            value="1"
                            <?= (int) $realisation['is_active'] === 1 ? 'checked' : '' ?>>

                        Afficher cette réalisation sur l’accueil
                    </label>

                    <button type="submit" class="adminButton">
                        Enregistrer les modifications
                    </button>

                    <a href="/admin/realisations" class="backDashboard">
                        Retour aux réalisations
                    </a>

                </form>

            <?php endif; ?>

        </section>

    </main>

</body>

</html>