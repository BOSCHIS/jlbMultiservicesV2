<?php
$pageTitle = "Ajouter une prestation";
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

            <h1>Ajouter une prestation</h1>

            <form
                method="POST"
                action="/admin/service/create"
                class="adminForm"
                enctype="multipart/form-data">

                <label>
                    Catégorie

                    <select name="category_id" required>

                        <option value="">
                            Choisir une catégorie
                        </option>

                        <?php foreach ($categories as $category) : ?>

                            <option value="<?= (int) $category['id_category'] ?>">
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
                        placeholder="exemple-nettoyage-vitres"
                        required>

                </label>

                <label>
                    Titre de la prestation

                    <input
                        type="text"
                        name="title"
                        required>

                </label>

                <label>
                    Description

                    <textarea
                        name="description"
                        rows="8"
                        required></textarea>

                </label>

                <label>
                    Image de la prestation

                    <input
                        type="file"
                        name="image"
                        accept="image/png, image/jpeg, image/webp">
                </label>

                <button type="submit" class="adminButton">
                    Ajouter
                </button>

                <a href="/admin/services" class="backDashboard">
                    Retour aux prestations
                </a>

            </form>

        </section>

    </main>

</body>

</html>