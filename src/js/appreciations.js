document.addEventListener("DOMContentLoaded", function () {
    // On sélectionne les boutons like et dislike du touite
    const likeButtons = document.querySelectorAll("#boutonLike");
    const dislikeButtons = document.querySelectorAll("#boutonDislike");

    // Gestionnaire d'événement pour le bouton "Like"
    likeButtons.forEach((likeButton) => {
        likeButton.addEventListener("click", function () {
            // Récupérez l'identifiant du touite associé
            const touiteElement = likeButton.closest(".touite");
            const touiteId = touiteElement.getAttribute("data-idTouite");

            // Vérifiez si l'utilisateur est connecté (à implémenter)
            if (!isUserLoggedIn()) {
                alert("Vous devez être connecté pour aimer un touite.");
                return;
            }

            // Envoyez la requête pour liker le touite
            updateTouiteScore(touiteId, 1); // 1 pour le like
        });
    });

    // Gestionnaire d'événement pour le bouton "Dislike"
    dislikeButtons.forEach((dislikeButton) => {
        dislikeButton.addEventListener("click", function () {
            // Récupérez l'identifiant du touite associé
            const touiteElement = dislikeButton.closest(".touite");
            const touiteId = touiteElement.getAttribute("data-idTouite");

            // Vérifiez si l'utilisateur est connecté (à implémenter)
            if (!isUserLoggedIn()) {
                alert("Vous devez être connecté pour ne pas aimer un touite.");
                return;
            }

            // Envoyez la requête pour disliker le touite
            updateTouiteScore(touiteId, -1); // -1 pour le dislike
        });
    });

    // Fonction pour mettre à jour le score du touite dans la base de données (à implémenter)
    function updateTouiteScore(touiteId, appreciation) {
        // Utilisez AJAX ou Fetch API pour envoyer une requête au serveur PHP pour mettre à jour le score
        // Exemple : fetch("/update-score.php", { method: "POST", body: JSON.stringify({ touiteId, appreciation }) })
        // Gérez les réponses du serveur dans cette fonction
    }

    // Fonction de vérification de la connexion de l'utilisateur (à implémenter)
    function isUserLoggedIn() {
        // Insérez votre logique de vérification ici
        return true; // Exemple : l'utilisateur est toujours considéré comme connecté
    }
});
