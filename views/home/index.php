<?php
$pageTitle = "JLB MULTISERVICES | Accueil";
$pageDescription = "Nettoyage, jardinage, débarras et petits travaux dans le Lot.";
$css = null;

require_once __DIR__ . '/../layout/header.php';
?>

<main>
    <article class="entreprise">
        <div class="entrepriseText">
            <h2>JLB MULTISERVICES</h2>
            <p>Je suis Jean-Luc Boschis, auto-entrepreneur et fondateur de JLB MULTISERVICES. </p>
            <p>Fort de plus de 25 années d’expérience dans le nettoyage, j’ai créé JLB MULTISERVICES pour proposer
                des prestations fiables, soignées et adaptées aux besoins du quotidien, aussi bien pour
                lesparticuliers que pour les professionnels.</p>
            <p>Basée près de Cahors, JLB MULTISERVICES intervient dans l'ensemble du département du Lot pour des
                prestations de nettoyage intérieur et extérieur, de multiservices, de petits bricolages, de
                jardinage léger et de débarras.</p>
            <p>Avec JLB MULTISERVICES, vous bénéficiez d’un interlocuteur unique, d’un travail réalisé avec sérieux,
                et de solutions pratiques pour vous faire gagner du temps et de la tranquillité.
            </p>
            <a href="/entreprise">En savoir plus &gt;</a>
        </div>
        <div class="entrepriseImage">
            <img src="/assets/media/local.webp" alt="image d'un local de l'entreprise JLB MULTISERVICES">
        </div>
    </article>

    <section class="services">

        <?php if (!empty($categories)) : ?>

            <?php foreach ($categories as $category) : ?>

                <?php
                $slugClass = htmlspecialchars($category['slug_category']);
                $contentClass = htmlspecialchars($category['slug_category']) . 'Content';
                ?>

                <article class="serviceItem">

                    <div class="image">

                        <?php if (!empty($category['image_category'])) : ?>

                            <img
                                src="/assets/media/<?= rawurlencode($category['image_category']) ?>"
                                alt="<?= htmlspecialchars($category['name_category']) ?>">

                        <?php else : ?>

                            <img
                                src="/assets/media/default_service.webp"
                                alt="Image par défaut d’une catégorie JLB MULTISERVICES">

                        <?php endif; ?>

                    </div>

                    <div class="serviceContent">

                        <h3><?= htmlspecialchars($category['name_category']) ?></h3>

                        <p>
                            <?= nl2br(htmlspecialchars($category['description_category'] ?? '')) ?>
                        </p>

                        <a href="/<?= rawurlencode($category['slug_category']) ?>">
                            Voir les détails &gt;
                        </a>

                    </div>

                </article>

            <?php endforeach; ?>

        <?php else : ?>

            <p>Aucune catégorie disponible pour le moment.</p>

        <?php endif; ?>

        <article class="serviceItem serviceSurMesure">

            <div class="image">
                <img
                    src="/assets/media/service_sur_mesure.webp"
                    alt="icône service sur mesure"
                    aria-label="image miniature représentant la catégorie Service sur mesure">
            </div>

            <div class="serviceSurMesureContent">

                <h3>Vous souhaitez un service sur mesure ?</h3>

                <p>
                    Vous avez un besoin spécifique ou particulier ?
                    Chez JLB MULTISERVICES, je m’adapte à votre situation et à vos contraintes.
                    </br>
                    Pour obtenir votre devis personnalisé, il vous suffit de remplir notre formulaire en ligne.
                </p>

                <a href="/contact">Contactez JLB MULTISERVICES &gt;</a>

            </div>

        </article>

    </section>

    <?php require_once __DIR__ . '/../components/realisations-preview.php'; ?>

    <article class="valeurs">
        <div class="valeursImage">
            <img src="/assets/media/valeurs.webp" alt="image représentant les valeurs de JLB MULTISERVICES">
        </div>
        <div class="valeursText">
            <h2>LES VALEURS DE JLB MULTISERVICES</h2>
            <p>Chez JLB MULTISERVICES, chaque intervention repose sur le sérieux et le soin du détail : je
                m’assure
                que chaque tâche soit réalisée avec rigueur et ponctualité, pour un résultat dont vous pouvez
                être
                pleinement satisfait.
            </p>
            <p>La confiance est essentielle : je privilégie la transparence et l’écoute, afin
                que nos échanges soient clairs et fiables. En tant qu’entreprise locale, je mise sur la
                proximité et
                la réactivité, en connaissant parfaitement le terrain autour de Cahors et en restant à l’écoute
                de
                vos besoins spécifiques.
            </p>
            <p> Fort de 25 ans d’expérience dans le nettoyage, je mets ce savoir-faire au
                service de prestations efficaces et durables, toujours adaptées à chaque situation. Et parce que
                la
                simplicité est au cœur de ma démarche, je m’efforce de rendre chaque étape claire et sans
                complication, de votre demande de devis jusqu’à la réalisation du service.
            </p>
            <a href="/entreprise">En savoir plus &gt;</a>
        </div>
    </article>
    <?php require_once __DIR__ . '/../components/map.php'; ?>
</main>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>