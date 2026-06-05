<?php
$realisations = $realisations ?? [];
?>

<?php if (!empty($realisations)) : ?>

    <section class="beforeAfterSection">

        <div class="beforeAfterHeader">

            <h2>Nos réalisations avant / après</h2>

            <p>
                Découvrez quelques exemples de travaux réalisés avec soin.
            </p>

        </div>

        <div class="beforeAfterCarouselWrapper">

            <button
                type="button"
                class="beforeAfterNav beforeAfterPrev"
                aria-label="Voir les réalisations précédentes">
                ‹
            </button>

            <div class="beforeAfterCarousel">

                <?php foreach ($realisations as $realisation) : ?>

                    <article class="beforeAfterItem">

                        <h3><?= htmlspecialchars($realisation['title']) ?></h3>

                        <div class="beforeAfterSlider">

                            <img
                                src="/assets/uploads/realisations/<?= rawurlencode($realisation['image_after']) ?>"
                                alt="Après <?= htmlspecialchars($realisation['title']) ?>"
                                class="afterImage">

                            <div class="beforeImageWrapper">

                                <img
                                    src="/assets/uploads/realisations/<?= rawurlencode($realisation['image_before']) ?>"
                                    alt="Avant <?= htmlspecialchars($realisation['title']) ?>"
                                    class="beforeImage">

                            </div>

                            <span class="beforeLabel">Avant</span>
                            <span class="afterLabel">Après</span>

                            <div class="sliderLine"></div>

                            <input
                                type="range"
                                min="0"
                                max="100"
                                value="50"
                                class="beforeAfterRange"
                                aria-label="Comparer l’image avant et après">

                        </div>

                    </article>

                <?php endforeach; ?>

            </div>

            <button
                type="button"
                class="beforeAfterNav beforeAfterNext"
                aria-label="Voir les réalisations suivantes">
                ›
            </button>

        </div>

    </section>

<?php endif; ?>