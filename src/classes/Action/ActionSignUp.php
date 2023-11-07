<?php

namespace touiteur\Action;

use touiteur\Auth\Auth;
use touiteur\Exception\AuthException;

class ActionSignUp extends Action
{

    public function execute(): string // équivalent à retourner le contenu html
    {
        $contenu_html = "";
        // Vérifie si c'est un GET, si oui, affiche le formulaire
        // Mettre un script js pour que les deux mots de passe soient les mêmes avant de pouvoir envoyer le form
        if ($this->http_method === 'GET') {
            $contenu_html .= <<<FORM
<form action="?action=signup" method="post">
        <label for="username">Nom d'utilisateur :</label>
        <input type="text" id="username" name="username" required>

        <label for="nom">Nom :</label>
        <input type="text" id="nom" name="nom" required>

        <label for="prenom">Prénom :</label>
        <input type="text" id="prenom" name="prenom" required>

        <label for="email">Email :</label>
        <input type="email" id="email" name="email" required>
        
        <label for="password">Mot de passe :</label>
        <input type="password" id="password" name="password" required>
        <br>
        <label for="confirmPassword">Confirmez le mot de passe :</label>
        <input type="password" id="confirmPassword" required>
        <p id="passwordMatchMessage"></p>
        <br>
        <input type="submit" name="submit" value="S'inscrire">
    </form>

    <script>
        const password = document.getElementById("password");
        const confirmPassword = document.getElementById("confirmPassword");
        const passwordMatchMessage = document.getElementById("passwordMatchMessage");
        const registrationForm = document.getElementById("registrationForm");

        function checkPasswordMatch() {
            if (password.value === confirmPassword.value) {
                passwordMatchMessage.textContent = "Les mots de passent correspondent.";
                passwordMatchMessage.style.color = "green";
                registrationForm.querySelector("button[type='submit']").removeAttribute("disabled");
            } else {
                passwordMatchMessage.textContent = "Les mots de passe ne correspondent pas.";
                passwordMatchMessage.style.color = "red";
                registrationForm.querySelector("button[type='submit']").setAttribute("disabled", "true");
            }
        }

        password.addEventListener("change", checkPasswordMatch);
        confirmPassword.addEventListener("input", checkPasswordMatch);
    </script>
FORM;
        }elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
            /*
             * Aspect sécurité ici
             * On nettoie tous les inputs du formulaire sauf le mot de passe
             */
            $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
            $nom = filter_var($_POST['nom'], FILTER_SANITIZE_STRING);
            $prenom = filter_var($_POST['prenom'], FILTER_SANITIZE_STRING);
            $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
            try {
                Auth::register($email, $_POST['password']);
                // Redirection vers la page de confirmation si l'inscription fonctionne
                $contenu_html .= "<h4>Vous êtes bien inscrit sur Toiteur, bienvenue !</h4>";
            }catch (AuthException $e) {
                $erreur = $e->getMessage();
                $contenu_html .= <<<FORMULAIRE
<form action="?action=signup" method="post">
        <label for="username">Nom d'utilisateur :</label>
        <input type="text" id="username" name="username" required>

        <label for="nom">Nom :</label>
        <input type="text" id="nom" name="nom" required>

        <label for="prenom">Prénom :</label>
        <input type="text" id="prenom" name="prenom" required>

        <label for="email">Email :</label>
        <input type="email" id="email" name="email" required>
        
        <label for="password">Mot de passe :</label>
        <input type="password" id="password" name="password" required>
        <br>
        <label for="confirmPassword">Confirmez le mot de passe :</label>
        <input type="password" id="confirmPassword" required>
        <p id="passwordMatchMessage"></p>
        <br>
        
        $erreur
        
        <input type="submit" name="submit" value="S'inscrire">
    </form>

    <script>
        const password = document.getElementById("password");
        const confirmPassword = document.getElementById("confirmPassword");
        const passwordMatchMessage = document.getElementById("passwordMatchMessage");
        const registrationForm = document.getElementById("registrationForm");

        function checkPasswordMatch() {
            if (password.value === confirmPassword.value) {
                passwordMatchMessage.textContent = "Les mots de passent correspondent.";
                passwordMatchMessage.style.color = "green";
                registrationForm.querySelector("button[type='submit']").removeAttribute("disabled");
            } else {
                passwordMatchMessage.textContent = "Les mots de passe ne correspondent pas.";
                passwordMatchMessage.style.color = "red";
                registrationForm.querySelector("button[type='submit']").setAttribute("disabled", "true");
            }
        }

        password.addEventListener("change", checkPasswordMatch);
        confirmPassword.addEventListener("input", checkPasswordMatch);
    </script>
FORMULAIRE;
            }
        }
        return $contenu_html;
    }
}