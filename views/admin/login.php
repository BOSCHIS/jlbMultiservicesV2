<?php
$pageTitle = "Connexion administrateur";
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

    <main class="adminLogin">

        <section class="adminCard">

            <img src="/assets/media/JLB_Multiservices_logo.webp"
                alt="Logo JLB MULTISERVICES"
                class="adminLogo">

            <h1>Connexion administrateur</h1>

            <?php if (!empty($_SESSION['error'])) : ?>
                <p class="adminError"><?= htmlspecialchars($_SESSION['error']) ?></p>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <form method="POST" action="/admin/login" class="adminForm">

                <label>
                    Identifiant
                    <input type="text" name="username" required>
                </label>

                <label>
                    Mot de passe
                    <input type="password" name="password" required>
                </label>

                <button type="submit" class="adminButton">
                    Se connecter
                </button>

            </form>

        </section>

    </main>

</body>

</html>