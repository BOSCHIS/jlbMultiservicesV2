<?php
$pageTitle = "Ajouter une catégorie";
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

    <main class="adminLogin">

        <section class="adminCard">

            <img
                src="/assets/media/JLB_Multiservices_logo.webp"
                alt="Logo JLB MULTISERVICES"
                class="adminLogo">

            <h1>Ajouter une catégorie</h1>

            <form
                method="POST"
                action="/admin/category/create"
                class="adminForm"
                enctype="multipart/form-data">

                <label>
                    Nom de la catégorie

                    <input
                        type="text"
                        name="name"
                        placeholder="Exemple : Nettoyage et entretien intérieur"
                        required>
                </label>

                <label>
                    Slug URL

                    <input
                        type="text"
                        name="slug"
                        placeholder="exemple : nettoyage"
                        required>
                </label>

                <label>
                    Description

                    <textarea
                        name="description"
                        rows="8"
                        placeholder="Décrivez brièvement cette catégorie de services."
                        required></textarea>
                </label>

                <label>
                    Ordre d'affichage

                    <input
                        type="number"
                        name="display_order"
                        min="1"
                        value="1"
                        required>
                </label>

                <label class="checkboxAdmin">
                    <input
                        type="checkbox"
                        name="is_active"
                        checked>

                    Afficher cette catégorie sur l’accueil
                </label>

                <label>
                    Image de la catégorie

                    <input
                        type="file"
                        name="image"
                        accept="image/png, image/jpeg, image/webp">
                </label>

                <button type="submit" class="adminButton">
                    Ajouter
                </button>

                <a href="/admin/categories" class="backDashboard">
                    Retour aux catégories
                </a>

            </form>

        </section>

    </main>

</body>

</html>