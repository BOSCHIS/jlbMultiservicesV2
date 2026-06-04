document.addEventListener("DOMContentLoaded", () => {

    const backToTop = document.querySelector(".backToTop");
    if (backToTop) {
        backToTop.style.display = "none";
        window.addEventListener("scroll", () => {
            if (window.scrollY > 60) {
                backToTop.style.display = "flex";
            } else {
                backToTop.style.display = "none";
            }
        });
    }


    const burger = document.querySelector(".burger");
    const nav = document.getElementById("nav");
    const closeNav = document.querySelector(".closeNav");

    if (burger && nav && closeNav) {
        burger.addEventListener("click", () => {
            nav.classList.toggle("open");
        });
        closeNav.addEventListener("click", () => {
            nav.classList.remove("open");
        });
    }

    const objectSelection = document.querySelectorAll('.entreprise, .valeurs, .services article, .zoneIntervention');

    objectSelection.forEach(element => element.classList.add('scrollAnimate'));


    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('show');
            }
        });
    });


    objectSelection.forEach(element => observer.observe(element));


    const mapClass = document.querySelector('.map');
    if (mapClass) {
        if (mapClass._leaflet_id) {
            mapClass._leaflet_id = null;
        }

        const map = L.map(mapClass);

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© <a href="http://www.openstreetmap.org/copyright" target="_blank">OpenStreetMap</a> contributors',
            maxZoom: 19
        }).addTo(map);

        map.scrollWheelZoom.disable();

        const contactApi = () => {
            fetch('https://data.laregion.fr/api/explore/v2.1/catalog/datasets/departements-d-occitanie/records?limit=20&refine=nom_officiel_departement%3A%22Lot%22')
                .then(response => response.json())
                .then(dataTrans => {

                    const coords = dataTrans.results[0].geo_shape.geometry.coordinates[0];
                    const latLngs = coords.map(c => [c[1], c[0]]);
                    const marker = L.marker([44.471444, 1.409054]).addTo(map);
                    const polygon = L.polygon(latLngs, {
                        color: '#16593a',
                        fillOpacity: 0.15,
                    }).addTo(map);

                    setTimeout(() => {
                        map.invalidateSize();
                        map.fitBounds(polygon.getBounds());
                    }, 0);
                })
                .catch(error => console.log("Erreur custom : " + error));
        };

        contactApi();
    }
    const form = document.getElementById("contactForm");

    if (form) {
        const messageSuccess = document.querySelector(".messageSuccess");
        const messageFail = document.querySelector(".messageFail");
        const closeMessageSuccess = document.querySelector(".closeButtonSuccess");
        const closeMessageFail = document.querySelector(".closeButtonFail");

        form.addEventListener("submit", async (e) => {
            e.preventDefault();

            const cgu = document.getElementById("cgu");
            const successText = document.getElementById("successText");
            const failText = document.getElementById("failText");

            if (!cgu.checked) {
                alert("Merci de cocher la case confirmant votre acceptation du traitement de vos données personnelles.");
                cgu.focus();
                return;
            }

            const formData = new FormData(form);

            try {
                const response = await fetch("/contact/send", {
                    method: "POST",
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    successText.textContent = result.message;
                    messageSuccess.classList.remove("close");
                    messageFail.classList.add("close");
                    form.reset();
                } else {
                    failText.textContent = result.message;
                    messageFail.classList.remove("close");
                    messageSuccess.classList.add("close");
                }
            } catch (err) {
                failText.textContent = "Une erreur est survenue lors de l'envoi du message. Veuillez réessayer.";
                messageFail.classList.remove("close");
                messageSuccess.classList.add("close");
            }
        });

        closeMessageSuccess.addEventListener("click", () => {
            messageSuccess.classList.add("close");
        });

        closeMessageFail.addEventListener("click", () => {
            messageFail.classList.add("close");
        });
    }

});