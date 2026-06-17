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

        <?php if (($category['slug_category'] ?? '') === 'nettoyage') : ?>

            <h1>Nettoyage</h1>

            <p>
                JLB MULTISERVICES vous accompagne pour vos besoins en nettoyage, qu’il s’agisse de sols, vitres, terrasses, vérandas ou autres surfaces à entretenir. Chaque intervention peut être adaptée selon vos besoins, votre logement ou vos locaux.
            </p>

        <?php else : ?>

            <h1><?= htmlspecialchars($category['name_category']) ?></h1>

            <?php if (!empty($category['description_category'])) : ?>

                <p>
                    <?= nl2br(htmlspecialchars($category['description_category'])) ?>
                </p>

            <?php else : ?>

                <p>
                    Découvrez les prestations proposées par JLB MULTISERVICES.
                    Chaque demande peut être adaptée selon vos besoins.
                </p>

            <?php endif; ?>

        <?php endif; ?>

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

            <?php foreach ($services as $index => $service) : ?>

                <article class="entreprise entreprisePage <?= $index % 2 === 1 ? 'entrepriseReverse' : '' ?>">

                    <div class="entrepriseText">

                        <h2><?= htmlspecialchars($service['title']) ?></h2>

                        <p>
                            <?= nl2br(htmlspecialchars($service['description_service'])) ?>
                        </p>

                        <a
                            href="/contact?service=<?= rawurlencode($service['slug']) ?>"
                            class="serviceQuoteLink">
                            Demander un devis &gt;
                        </a>

                    </div>

                    <div class="entrepriseImage">

                        <?php if (!empty($service['image'])) : ?>

                            <img
                                src="/assets/uploads/services/<?= rawurlencode($service['image']) ?>"
                                alt="<?= htmlspecialchars($service['title']) ?>">

                        <?php else : ?>

                            <img
                                src="/assets/media/default_service.webp"
                                alt="Image par défaut d’une prestation JLB MULTISERVICES">

                        <?php endif; ?>

                    </div>

                </article>

            <?php endforeach; ?>

        <?php endif; ?>

    </section>


</main>
<?php require_once __DIR__ . '/../components/map.php'; ?>
<?php require_once __DIR__ . '/../layout/footer.php'; ?>