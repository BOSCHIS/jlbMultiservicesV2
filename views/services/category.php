<?php

$category = $category ?? null;
$services = $services ?? [];

if (!$category) {
    header('Location: /');
    exit;
}

$pageTitle = $category['name_category'] . " | JLB MULTISERVICES";
$pageDescription = "Découvrez les prestations de JLB MULTISERVICES pour la catégorie " . $category['name_category'] . ".";
$css = null;

require_once __DIR__ . '/../layout/header.php';

?>
<main class="categoryPage">

    <section class="categoryHeader">

        <h1><?= htmlspecialchars($category['name_category']) ?></h1>

        <p>
            Découvrez les prestations proposées par JLB MULTISERVICES dans cette catégorie.
            Chaque demande peut être adaptée selon vos besoins.
        </p>

    </section>

    <section class="categoryServices">

        <?php if (empty($services)) : ?>

            <div class="emptyCategory">

                <p>
                    Aucune prestation n’est disponible pour le moment dans cette catégorie.
                </p>

                <a href="/contact" class="serviceContactButton">
                    Demander un devis personnalisé
                </a>

            </div>

        <?php else : ?>

            <?php foreach ($services as $service) : ?>

                <article class="categoryServiceCard">

                    <div class="categoryServiceImage">

                        <?php if (!empty($service['image'])) : ?>

                            <img
                                src="/assets/uploads/services/<?= rawurlencode($service['image']) ?>"
                                alt="<?= htmlspecialchars($service['title']) ?>">

                        <?php else : ?>

                            <img
                                src="/assets/media/default_service.png"
                                alt="Image par défaut d’une prestation JLB MULTISERVICES">

                        <?php endif; ?>

                    </div>

                    <div class="categoryServiceContent">

                        <h2><?= htmlspecialchars($service['title']) ?></h2>

                        <p>
                            <?= nl2br(htmlspecialchars($service['description_service'])) ?>
                        </p>

                        <div class="categoryServiceActions">

                            <a
                                href="/service?slug=<?= rawurlencode($service['slug']) ?>"
                                class="detailsButton">
                                Voir les détails
                            </a>

                            <a
                                href="/contact?service=<?= rawurlencode($service['slug']) ?>"
                                class="serviceContactButton">
                                Demander un devis
                            </a>

                        </div>

                    </div>

                </article>

            <?php endforeach; ?>

        <?php endif; ?>

    </section>

</main>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>