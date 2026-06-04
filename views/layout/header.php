<?php

$pageTitle = $pageTitle ?? "JLB MULTISERVICES | Nettoyage et multiservices dans le Lot";

$pageDescription = $pageDescription ??
    "Vous recherchez un service de nettoyage, jardinage, débarras ou petits travaux dans le Lot ? JLB MULTISERVICES intervient pour particuliers et professionnels.";

$css = $css ?? null;

$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="/favicon.svg" />
    <link rel="shortcut icon" href="/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png" />
    <meta name="apple-mobile-web-app-title" content="MyWebSite" />
    <link rel="manifest" href="/site.webmanifest" />

    <title><?= htmlspecialchars($pageTitle) ?></title>
    <meta name="description" content="<?= htmlspecialchars($pageDescription) ?>">

    <link rel="stylesheet" href="/assets/style/main.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">

    <?php if (!empty($css)) : ?>
        <link rel="stylesheet" href="<?= htmlspecialchars($css) ?>">
    <?php endif; ?>
</head>

<body id="top">

    <header>

        <img src="/assets/media/JLB_Multiservices_logo.webp"
            alt="Logo de JLB Multiservices"
            class="logo">

        <button class="burger"
            aria-label="Ouvrir le menu"
            title="Ouvrir le menu de navigation">
            ☰
        </button>

        <nav id="nav">

            <button class="closeNav"
                aria-label="Fermer le menu"
                title="Fermer le menu">
                ✕
            </button>

            <ul>

                <li>
                    <a href="/"
                        class="<?= $currentPath === '/' ? 'active' : '' ?>">
                        Accueil
                    </a>
                </li>

                <li>
                    <a href="/nettoyage"
                        class="<?= $currentPath === '/nettoyage' ? 'active' : '' ?>">
                        Nettoyage
                    </a>
                </li>

                <li>
                    <a href="/bricolage"
                        class="<?= $currentPath === '/bricolage' ? 'active' : '' ?>">
                        Petit bricolage
                    </a>
                </li>

                <li>
                    <a href="/jardinage"
                        class="<?= $currentPath === '/jardinage' ? 'active' : '' ?>">
                        Jardinage
                    </a>
                </li>

                <li>
                    <a href="/debarras"
                        class="<?= $currentPath === '/debarras' ? 'active' : '' ?>">
                        Débarras
                    </a>
                </li>

                <li>
                    <a href="/entreprise"
                        class="<?= $currentPath === '/entreprise' ? 'active' : '' ?>">
                        L'entreprise
                    </a>
                </li>

                <li>
                    <a class="btnContact <?= $currentPath === '/contact' ? 'active' : '' ?>"
                        title="Accéder au formulaire de demande de devis"
                        href="/contact">
                        Contact
                    </a>
                </li>

            </ul>

        </nav>
    </header>

    <?php
    $currentPath = rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

    if ($currentPath === '') {
        $currentPath = '/';
    }

    $excludedContactBannerPages = [
        '/contact',
        '/mentions-legales',
        '/conditions-generales-utilisation',
        '/politique-confidentialite'
    ];
    ?>

    <?php if (!in_array($currentPath, $excludedContactBannerPages, true)) : ?>
        <?php require_once __DIR__ . '/../components/contact-banner.php'; ?>
    <?php endif; ?>