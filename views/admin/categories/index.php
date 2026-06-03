<?php
$pageTitle = "Gestion des catégories";
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

    <main class="adminDashboard">

        <section class="adminCard contactsCard">

            <img
                src="/assets/media/JLB_Multiservices_logo.webp"
                alt="Logo JLB MULTISERVICES"
                class="adminLogoSmall">

            <h1>Gestion des catégories</h1>
            <?php if (!empty($_SESSION['admin_error'])) : ?>

                <div class="adminAlertError">
                    <?= htmlspecialchars($_SESSION['admin_error']) ?>
                </div>

                <?php unset($_SESSION['admin_error']); ?>

            <?php endif; ?>

            <?php if (!empty($_SESSION['admin_success'])) : ?>

                <div class="adminAlertSuccess">
                    <?= htmlspecialchars($_SESSION['admin_success']) ?>
                </div>

                <?php unset($_SESSION['admin_success']); ?>

            <?php endif; ?>

            <div class="serviceActions">

                <a href="/admin/dashboard" class="backDashboard">
                    Retour au dashboard
                </a>

                <a href="/admin/category/create" class="adminButton addButton">
                    Ajouter une catégorie
                </a>

            </div>

            <?php if (empty($categories)) : ?>

                <p>Aucune catégorie enregistrée.</p>

            <?php else : ?>

                <table class="adminTable">

                    <thead>
                        <tr>
                            <th>Ordre</th>
                            <th>Image</th>
                            <th>Nom</th>
                            <th>Slug</th>
                            <th>Description</th>
                            <th>Visible</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php foreach ($categories as $category) : ?>

                            <tr>

                                <td data-label="Ordre">
                                    <?= (int) $category['display_order'] ?>
                                </td>

                                <td data-label="Image">
                                    <?php if (!empty($category['image_category'])) : ?>

                                        <img
                                            src="/assets/media/<?= rawurlencode($category['image_category']) ?>"
                                            alt="<?= htmlspecialchars($category['name_category']) ?>"
                                            class="adminServiceImage">

                                    <?php else : ?>

                                        <span>Aucune image</span>

                                    <?php endif; ?>
                                </td>

                                <td data-label="Nom">
                                    <?= htmlspecialchars($category['name_category']) ?>
                                </td>

                                <td data-label="Slug">
                                    <?= htmlspecialchars($category['slug_category']) ?>
                                </td>

                                <td data-label="Description" class="messageCell">
                                    <?= nl2br(htmlspecialchars($category['description_category'] ?? '')) ?>
                                </td>

                                <td data-label="Visible">
                                    <?= (int) $category['is_active'] === 1 ? 'Oui' : 'Non' ?>
                                </td>

                                <td data-label="Actions">

                                    <a
                                        class="editBtn"
                                        href="/admin/category/edit?id=<?= (int) $category['id_category'] ?>">
                                        Modifier
                                    </a>

                                    <form
                                        method="POST"
                                        action="/admin/category/delete"
                                        class="deleteForm"
                                        onsubmit="return confirm('Supprimer cette catégorie ? Les prestations liées peuvent être impactées.')">

                                        <input
                                            type="hidden"
                                            name="id"
                                            value="<?= (int) $category['id_category'] ?>">

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