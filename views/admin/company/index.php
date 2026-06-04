<?php
$pageTitle = "Gestion de la page entreprise";
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

    <main class="adminDashboard">

        <section class="adminCard contactsCard">

            <img
                src="/assets/media/JLB_Multiservices_logo.webp"
                alt="Logo JLB MULTISERVICES"
                class="adminLogoSmall">

            <h1>Gestion de la page entreprise</h1>

            <div class="serviceActions">

                <a href="/admin/dashboard" class="backDashboard">
                    Retour au dashboard
                </a>

                <a href="/admin/entreprise/create" class="adminButton addButton">
                    Ajouter un bloc
                </a>

            </div>

            <?php if (empty($companyContents)) : ?>

                <p>Aucun bloc de contenu enregistré.</p>

            <?php else : ?>

                <table class="adminTable companyTable">

                    <thead>
                        <tr>
                            <th>Ordre</th>
                            <th>Image</th>
                            <th>Titre</th>
                            <th>Contenu</th>
                            <th>Visible</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php foreach ($companyContents as $contentBlock) : ?>

                            <tr>

                                <td data-label="Ordre">
                                    <?= (int) $contentBlock['display_order'] ?>
                                </td>

                                <td data-label="Image">
                                    <?php if (!empty($contentBlock['image'])) : ?>

                                        <img
                                            src="/assets/media/<?= rawurlencode($contentBlock['image']) ?>"
                                            alt="<?= htmlspecialchars($contentBlock['title']) ?>"
                                            class="adminServiceImage">

                                    <?php else : ?>

                                        <span>Aucune image</span>

                                    <?php endif; ?>
                                </td>

                                <td data-label="Titre">
                                    <?= htmlspecialchars($contentBlock['title']) ?>
                                </td>

                                <td data-label="Contenu" class="messageCell">
                                    <?= nl2br(htmlspecialchars($contentBlock['content'])) ?>
                                </td>

                                <td data-label="Visible">
                                    <?= (int) $contentBlock['is_active'] === 1 ? 'Oui' : 'Non' ?>
                                </td>

                                <td data-label="Actions">

                                    <a
                                        class="editBtn"
                                        href="/admin/entreprise/edit?id=<?= (int) $contentBlock['id_company_content'] ?>">
                                        Modifier
                                    </a>

                                    <form
                                        method="POST"
                                        action="/admin/entreprise/delete"
                                        class="deleteForm"
                                        onsubmit="return confirm('Supprimer ce bloc de contenu ?')">

                                        <input
                                            type="hidden"
                                            name="id"
                                            value="<?= (int) $contentBlock['id_company_content'] ?>">

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