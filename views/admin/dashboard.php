<?php
$pageTitle = "Dashboard administrateur";
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

            <a href="/admin/realisations" class="adminButton">
                Gérer les réalisations avant/après
            </a>

            <a href="/admin/entreprise" class="adminButton">
                Gérer la page entreprise
            </a>

        </nav>

        <a href="/admin/logout" class="logoutBtn">
            Déconnexion
        </a>

    </main>

</body>

</html>