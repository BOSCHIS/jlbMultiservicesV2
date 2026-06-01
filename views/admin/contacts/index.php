<?php

$pageTitle = "Demandes de devis";

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
                alt="Logo"
                class="adminLogoSmall">

            <h1>Demandes de devis</h1>

            <a href="/admin/dashboard" class="backDashboard">
                Retour dashboard
            </a>

            <?php if (empty($contacts)) : ?>

                <p>Aucune demande enregistrée.</p>

            <?php else : ?>

                <table class="adminTable">

                    <thead>

                        <tr>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Téléphone</th>
                            <th>Message</th>
                            <th>Action</th>
                        </tr>

                    </thead>

                    <tbody>

                        <?php foreach ($contacts as $contact) : ?>

                            <tr>

                                <td>
                                    <?= htmlspecialchars($contact['name_contact']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($contact['email_contact']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($contact['telephone_contact']) ?>
                                </td>

                                <td class="messageCell">
                                    <?= htmlspecialchars($contact['message_contact']) ?>
                                </td>

                                <td>

                                    <a
                                        class="deleteBtn"
                                        href="/admin/contact/delete?id=<?= $contact['id_contact'] ?>"
                                        onclick="return confirm('Supprimer cette demande ?')">

                                        Supprimer

                                    </a>

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