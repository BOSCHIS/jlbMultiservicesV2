<?php

$pageTitle = "Entreprise | JLB MULTISERVICES";
$pageDescription = "Découvrez JLB MULTISERVICES, entreprise locale de multiservices dans le Lot.";
$css = null;

$companyContents = $companyContents ?? [];

require_once __DIR__ . '/../layout/header.php';

?>

<main>

    <?php if (empty($companyContents)) : ?>

        <article class="entreprise entreprisePage">

            <div class="entrepriseText">
                <h2>JLB MULTISERVICES</h2>

                <p>
                    Le contenu de présentation de l’entreprise n’est pas encore disponible.
                </p>
            </div>

        </article>

    <?php else : ?>

        <?php foreach ($companyContents as $index => $contentBlock) : ?>

            <article class="entreprise entreprisePage <?= $index % 2 === 1 ? 'entrepriseReverse' : '' ?>">

                <div class="entrepriseText">

                    <h2><?= htmlspecialchars($contentBlock['title']) ?></h2>

                    <p>
                        <?= nl2br(htmlspecialchars($contentBlock['content'])) ?>
                    </p>

                </div>

                <div class="entrepriseImage">

                    <?php if (!empty($contentBlock['image'])) : ?>

                        <img
                            src="/assets/media/<?= rawurlencode($contentBlock['image']) ?>"
                            alt="<?= htmlspecialchars($contentBlock['title']) ?>">

                    <?php else : ?>

                        <img
                            src="/assets/media/logo_jlb_multiservices.png"
                            alt="Image de présentation de JLB MULTISERVICES">

                    <?php endif; ?>

                </div>

            </article>

        <?php endforeach; ?>

    <?php endif; ?>

    <?php require_once __DIR__ . '/../components/map.php'; ?>

</main>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>