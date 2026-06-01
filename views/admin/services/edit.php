<?php
$pageTitle = "Modifier une prestation";
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

            <h1>Modifier une prestation</h1>

            <?php if (!$service) : ?>

                <p>Prestation introuvable.</p>

                <a href="/admin/services" class="backDashboard">
                    Retour aux prestations
                </a>

            <?php else : ?>

                <form
                    method="POST"
                    action="/admin/service/edit?id=<?= (int) $service['id_service'] ?>"
                    class="adminForm"
                    enctype="multipart/form-data">

                    <label>
                        Catégorie

                        <select name="category_id" required>

                            <option value="">
                                Choisir une catégorie
                            </option>

                            <?php foreach ($categories as $category) : ?>

                                <option
                                    value="<?= (int) $category['id_category'] ?>"
                                    <?= (int) $category['id_category'] === (int) $service['category_id'] ? 'selected' : '' ?>>

                                    <?= htmlspecialchars($category['name_category']) ?>

                                </option>

                            <?php endforeach; ?>

                        </select>

                    </label>

                    <label>
                        Slug URL

                        <input
                            type="text"
                            name="slug"
                            value="<?= htmlspecialchars($service['slug']) ?>"
                            required>

                    </label>

                    <label>
                        Titre de la prestation

                        <input
                            type="text"
                            name="title"
                            value="<?= htmlspecialchars($service['title']) ?>"
                            required>

                    </label>

                    <label>
                        Description

                        <textarea
                            name="description"
                            rows="8"
                            required><?= htmlspecialchars($service['description_service']) ?></textarea>

                    </label>

                    <?php if (!empty($service['image'])) : ?>

                        <div class="currentImageBox">

                            <p>Image actuelle :</p>

                            <img
                                src="/assets/uploads/services/<?= htmlspecialchars($service['image']) ?>"
                                alt="<?= htmlspecialchars($service['title']) ?>"
                                class="adminServiceImage">

                        </div>

                    <?php endif; ?>

                    <label>
                        Nouvelle image de la prestation

                        <input
                            type="file"
                            name="image"
                            accept="image/png, image/jpeg, image/webp">

                    </label>

                    <label>
                        Ordre d'affichage

                        <input
                            type="number"
                            name="display_order"
                            min="1"
                            value="<?= (int) $service['display_order'] ?>"
                            required>
                    </label>

                    <button type="submit" class="adminButton">
                        Enregistrer les modifications
                    </button>

                    <a href="/admin/services" class="backDashboard">
                        Retour aux prestations
                    </a>

                </form>

            <?php endif; ?>

        </section>

    </main>

</body>

</html>