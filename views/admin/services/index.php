<?php
$pageTitle = "Gestion des prestations";
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

            <h1>Gestion des prestations</h1>

            <div class="serviceActions">

                <a href="/admin/dashboard" class="backDashboard">
                    Retour au dashboard
                </a>

                <a href="/admin/service/create" class="adminButton addButton">
                    Ajouter une prestation
                </a>

            </div>

            <?php if (empty($services)) : ?>

                <p>Aucune prestation enregistrée.</p>

            <?php else : ?>

                <table class="adminTable">

                    <thead>
                        <tr>
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

                                <td>
                                    <?= (int) $service['display_order'] ?>
                                </td>

                                <td>
                                    <?php if (!empty($service['image'])) : ?>

                                        <img
                                            src="/assets/uploads/services/<?= rawurlencode($service['image']) ?>"
                                            alt="<?= htmlspecialchars($service['title']) ?>"
                                            class="adminServiceImage">

                                    <?php else : ?>

                                        <span>Aucune image</span>

                                    <?php endif; ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($service['title']) ?>
                                </td>

                                <td class="messageCell">
                                    <?= nl2br(htmlspecialchars($service['description_service'])) ?>
                                </td>

                                <td>
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