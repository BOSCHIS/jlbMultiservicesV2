<?php
$pageTitle = "Dashboard administrateur";
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

        <img
            src="/assets/media/JLB_Multiservices_logo.webp"
            alt="Logo JLB MULTISERVICES"
            class="adminLogo">

        <h1>Dashboard administrateur</h1>

        <p>
            Bonjour <?= htmlspecialchars($_SESSION['admin']['username']) ?>.
        </p>

        <nav class="adminNav">

            <a href="/admin/contacts">
                Demandes de devis
            </a>

            <a href="/admin/categories" class="adminButton">
                Gérer les catégories
            </a>

            <a href="/admin/services">
                Prestations
            </a>

        </nav>

        <a href="/admin/logout" class="logoutBtn">
            Déconnexion
        </a>

    </main>

</body>

</html>