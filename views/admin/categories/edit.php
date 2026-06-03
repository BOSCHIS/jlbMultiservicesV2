<?php

$pageTitle = "Modifier une catégorie";

$category = $category ?? null;

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

            <h1>Modifier une catégorie</h1>

            <?php if (!$category) : ?>

                <p>Catégorie introuvable.</p>

                <a href="/admin/categories" class="backDashboard">
                    Retour aux catégories
                </a>

            <?php else : ?>

                <form
                    method="POST"
                    action="/admin/category/edit?id=<?= (int) $category['id_category'] ?>"
                    class="adminForm"
                    enctype="multipart/form-data">

                    <label>
                        Nom de la catégorie

                        <input
                            type="text"
                            name="name"
                            value="<?= htmlspecialchars($category['name_category']) ?>"
                            required>
                    </label>

                    <label>
                        Slug URL

                        <input
                            type="text"
                            name="slug"
                            value="<?= htmlspecialchars($category['slug_category']) ?>"
                            required>
                    </label>

                    <label>
                        Description

                        <textarea
                            name="description"
                            rows="8"
                            required><?= htmlspecialchars($category['description_category'] ?? '') ?></textarea>
                    </label>

                    <label>
                        Ordre d'affichage

                        <input
                            type="number"
                            name="display_order"
                            min="1"
                            value="<?= (int) $category['display_order'] ?>"
                            required>
                    </label>

                    <label class="checkboxAdmin">
                        <input
                            type="checkbox"
                            name="is_active"
                            value="1"
                            <?= (int) $category['is_active'] === 1 ? 'checked' : '' ?>>

                        Afficher cette catégorie sur l’accueil
                    </label>

                    <div class="currentImageBox">

                        <p>Image actuelle :</p>

                        <?php if (!empty($category['image_category'])) : ?>

                            <img
                                src="/assets/media/<?= rawurlencode($category['image_category']) ?>"
                                alt="<?= htmlspecialchars($category['name_category']) ?>"
                                class="adminServiceImage">

                        <?php else : ?>

                            <img
                                src="/assets/media/default_service.webp"
                                alt="Image par défaut d’une catégorie JLB MULTISERVICES"
                                class="adminServiceImage">

                        <?php endif; ?>

                    </div>

                    <label>
                        Nouvelle image de la catégorie

                        <input
                            type="file"
                            name="image"
                            accept="image/png, image/jpeg, image/webp">
                    </label>

                    <button type="submit" class="adminButton">
                        Enregistrer les modifications
                    </button>

                    <a href="/admin/categories" class="backDashboard">
                        Retour aux catégories
                    </a>

                </form>

            <?php endif; ?>

        </section>

    </main>

</body>

</html>