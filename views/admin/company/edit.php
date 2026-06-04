<?php

$pageTitle = "Modifier un bloc entreprise";

$companyContent = $companyContent ?? null;

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

            <h1>Modifier un bloc entreprise</h1>

            <?php if (!$companyContent) : ?>

                <p>Bloc entreprise introuvable.</p>

                <a href="/admin/entreprise" class="backDashboard">
                    Retour à la page entreprise
                </a>

            <?php else : ?>

                <form
                    method="POST"
                    action="/admin/entreprise/edit?id=<?= (int) $companyContent['id_company_content'] ?>"
                    class="adminForm"
                    enctype="multipart/form-data">

                    <label>
                        Titre

                        <input
                            type="text"
                            name="title"
                            value="<?= htmlspecialchars($companyContent['title']) ?>"
                            required>
                    </label>

                    <label>
                        Contenu

                        <textarea
                            name="content"
                            rows="12"
                            required><?= htmlspecialchars($companyContent['content']) ?></textarea>
                    </label>

                    <label>
                        Ordre d'affichage

                        <input
                            type="number"
                            name="display_order"
                            min="1"
                            value="<?= (int) $companyContent['display_order'] ?>"
                            required>
                    </label>

                    <label class="checkboxAdmin">
                        <input
                            type="checkbox"
                            name="is_active"
                            value="1"
                            <?= (int) $companyContent['is_active'] === 1 ? 'checked' : '' ?>>

                        Afficher ce bloc sur la page entreprise
                    </label>

                    <div class="currentImageBox">

                        <p>Image actuelle :</p>

                        <?php if (!empty($companyContent['image'])) : ?>

                            <img
                                src="/assets/media/<?= rawurlencode($companyContent['image']) ?>"
                                alt="<?= htmlspecialchars($companyContent['title']) ?>"
                                class="adminServiceImage">

                        <?php else : ?>

                            <span>Aucune image</span>

                        <?php endif; ?>

                    </div>

                    <label>
                        Nouvelle image

                        <input
                            type="file"
                            name="image"
                            accept="image/png, image/jpeg, image/webp">
                    </label>

                    <button type="submit" class="adminButton">
                        Enregistrer les modifications
                    </button>

                    <a href="/admin/entreprise" class="backDashboard">
                        Retour à la page entreprise
                    </a>

                </form>

            <?php endif; ?>

        </section>

    </main>

</body>

</html>