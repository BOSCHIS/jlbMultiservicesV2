<?php

$pageTitle = "Gestion des prestations";

$services = $services ?? [];
$categories = $categories ?? [];
$selectedCategoryId = $selectedCategoryId ?? null;

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?></title>
    <link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96">
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <link rel="shortcut icon" href="/favicon.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <meta name="apple-mobile-web-app-title" content="JLB Multiservices">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="stylesheet" href="/assets/style/admin.css">
</head>

<body>

    <main class="adminDashboard">

        <section class="adminCard contactsCard">

            <img
                src="/assets/media/JLB_Multiservices_logo.webp"
                alt="Logo JLB MULTISERVICES"
                class="adminLogoSmall">

            <h1>Gestion des prestations</h1>

            <div class="serviceActions">

                <a href="/admin/dashboard" class="backDashboard">
                    Retour au dashboard
                </a>

                <a href="/admin/service/create" class="adminButton addButton">
                    Ajouter une prestation
                </a>

            </div>

            <form method="GET" action="/admin/services" class="adminFilterForm">

                <label>
                    Filtrer par catégorie

                    <select name="category_id" onchange="this.form.submit()">

                        <option value="">
                            Toutes les catégories
                        </option>

                        <?php foreach ($categories as $category) : ?>

                            <option
                                value="<?= (int) $category['id_category'] ?>"
                                <?= (int) $selectedCategoryId === (int) $category['id_category'] ? 'selected' : '' ?>>

                                <?= htmlspecialchars($category['name_category']) ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                </label>

            </form>

            <?php if (empty($services)) : ?>

                <p>Aucune prestation enregistrée.</p>

            <?php else : ?>

                <table class="adminTable serviceTable">

                    <thead>
                        <tr>
                            <th>Catégorie</th>
                            <th>Ordre</th>
                            <th>Image</th>
                            <th>Titre</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php foreach ($services as $service) : ?>

                            <tr>

                                <td data-label="Catégorie">
                                    <?= htmlspecialchars($service['name_category'] ?? 'Aucune catégorie') ?>
                                </td>

                                <td data-label="Ordre">
                                    <?= (int) $service['display_order'] ?>
                                </td>

                                <td data-label="Image">
                                    <?php if (!empty($service['image'])) : ?>

                                        <img
                                            src="/assets/uploads/services/<?= rawurlencode($service['image']) ?>"
                                            alt="<?= htmlspecialchars($service['title']) ?>"
                                            class="adminServiceImage">

                                    <?php else : ?>

                                        <span>Aucune image</span>

                                    <?php endif; ?>
                                </td>

                                <td data-label="Titre">
                                    <?= htmlspecialchars($service['title']) ?>
                                </td>

                                <td data-label="Description" class="messageCell">
                                    <?= nl2br(htmlspecialchars($service['description_service'])) ?>
                                </td>

                                <td data-label="Actions">

                                    <div class="tableActions">

                                        <a
                                            class="editBtn"
                                            href="/admin/service/edit?id=<?= (int) $service['id_service'] ?>">
                                            Modifier
                                        </a>

                                        <form
                                            method="POST"
                                            action="/admin/service/delete"
                                            class="deleteForm"
                                            onsubmit="return confirm('Supprimer cette prestation ?')">

                                            <input
                                                type="hidden"
                                                name="id"
                                                value="<?= (int) $service['id_service'] ?>">

                                            <input
                                                type="hidden"
                                                name="csrf_token"
                                                value="<?= htmlspecialchars($_SESSION['admin_csrf_token'] ?? '') ?>">

                                            <button type="submit" class="deleteBtn">
                                                Supprimer
                                            </button>

                                        </form>

                                    </div>

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