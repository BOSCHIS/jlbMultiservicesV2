<?php
$pageTitle = "Gestion des réalisations avant/après";

$realisations = $realisations ?? [];
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

            <h1>Gestion des réalisations avant/après</h1>

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

                <a href="/admin/realisations/create" class="adminButton addButton">
                    Ajouter une réalisation
                </a>

            </div>

            <?php if (empty($realisations)) : ?>

                <p>Aucune réalisation enregistrée.</p>

            <?php else : ?>

                <table class="adminTable realisationTable">

                    <thead>
                        <tr>
                            <th>Ordre</th>
                            <th>Avant</th>
                            <th>Après</th>
                            <th>Titre</th>
                            <th>Visible</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php foreach ($realisations as $realisation) : ?>

                            <tr>

                                <td data-label="Ordre">
                                    <?= (int) $realisation['display_order'] ?>
                                </td>

                                <td data-label="Avant">
                                    <img
                                        src="/assets/uploads/realisations/<?= rawurlencode($realisation['image_before']) ?>"
                                        alt="Avant <?= htmlspecialchars($realisation['title']) ?>"
                                        class="adminServiceImage">
                                </td>

                                <td data-label="Après">
                                    <img
                                        src="/assets/uploads/realisations/<?= rawurlencode($realisation['image_after']) ?>"
                                        alt="Après <?= htmlspecialchars($realisation['title']) ?>"
                                        class="adminServiceImage">
                                </td>

                                <td data-label="Titre">
                                    <?= htmlspecialchars($realisation['title']) ?>
                                </td>

                                <td data-label="Visible">
                                    <?= (int) $realisation['is_active'] === 1 ? 'Oui' : 'Non' ?>
                                </td>

                                <td data-label="Actions">

                                    <a
                                        class="editBtn"
                                        href="/admin/realisations/edit?id=<?= (int) $realisation['id_realisation'] ?>">
                                        Modifier
                                    </a>

                                    <form
                                        method="POST"
                                        action="/admin/realisations/delete"
                                        class="deleteForm"
                                        onsubmit="return confirm('Supprimer cette réalisation avant/après ?')">

                                        <input
                                            type="hidden"
                                            name="id"
                                            value="<?= (int) $realisation['id_realisation'] ?>">

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