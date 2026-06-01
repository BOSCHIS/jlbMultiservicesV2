<?php
$pageTitle = $service['title'];
?>

<?php require_once __DIR__ . '/../layout/header.php'; ?>

<main class="servicePage">

    <section class="serviceHero">

        <?php if (!empty($service['image'])) : ?>

            <div class="serviceHeroImageWrapper">

                <img
                    src="/assets/uploads/services/<?= rawurlencode($service['image']) ?>"
                    alt="<?= htmlspecialchars($service['title']) ?>"
                    class="serviceHeroImage">

            </div>

        <?php endif; ?>

        <div class="serviceHeroContent">

            <h1><?= htmlspecialchars($service['title']) ?></h1>

            <p>
                <?= nl2br(htmlspecialchars($service['description_service'])) ?>
            </p>

            <a
                href="/contact?service=<?= rawurlencode($service['slug']) ?>"
                class="serviceContactButton">
                Demander un devis
            </a>

        </div>

    </section>

</main>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>