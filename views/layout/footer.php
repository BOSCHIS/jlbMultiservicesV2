<footer>
    <div class="footerLeft">
        <img src="/assets/media/JLB_Multiservices_logo.webp" alt="Logo JLB Multiservices">
        <ul>
            <li><a href="/mentions-legales" class="legalLink">Mentions légales</a></li>
            <li><a href="/conditions-generales-utilisation" class="cgu">Conditions générales d'utilisation</a></li>
        </ul>
    </div>

    <nav class="footerMiddle">
        <h3>Navigation</h3>
        <ul>
            <li><a href="/">Accueil</a></li>
            <li><a href="/nettoyage">Nettoyage</a></li>
            <li><a href="/bricolage">Petit bricolage</a></li>
            <li><a href="/jardinage">Jardinage</a></li>
            <li><a href="/debarras">Débarras</a></li>
            <li><a href="/entreprise">L'entreprise</a></li>
        </ul>
    </nav>

    <div class="footerRight">
        <h3>Contact</h3>
        <ul>
            <li>
                <img class="iconFooter" src="/assets/media/marqueur.png" alt="icône marqueur"
                    aria-label="icône d'un marqueur, accompagnant l'adresse postale de JLB MULTISERVICES">
                <span>9 rue Antoine de Saint-Exupéry, 46090 PRADINES</span>
            </li>
            <li>
                <img class="iconFooter" src="/assets/media/phone.png" alt="icône téléphone"
                    aria-label="icône téléphone, accompagnant les coordonnées téléphoniques de l'entreprise JLB MULTISERVICES">
                <span>06 49 46 24 98</span>
            </li>
        </ul>

        <p class="copyright">
            2026 © JLB Multiservices – Tous droits réservés
        </p>
    </div>

</footer>

<a href="#top" class="backToTop" title="Retour en haut de page"
    aria-label="Bouton avec une flèche vers le haut pour retourner en haut de la page">
    <img src="/assets/media/top_arrow.svg" alt="flèche vers le haut">
</a>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<?php if (getenv('APP_ENV') !== 'production'): ?>
    <script src="/assets/script/main.js"></script>
<?php endif; ?>
</body>

</html>