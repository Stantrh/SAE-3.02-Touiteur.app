<?php

namespace touiteur\Action;

use touiteur\Auth\Auth;
use touiteur\Exception\AuthException;
class ActionSignIn extends Action
{
    /**
     * @throws AuthException
     */
    public function execute(): string
    {
        $contenu_html = "";
        if($this->http_method === 'GET'){
            $contenu_html .= <<<FORM
<form class="connexion" action="?action=signin" method="post">
            <label for="username">Nom d'utilisateur :</label>
            <input type="text" id="username" name="username" required><br><br>
            
            <label for="password">Mot de passe : </label>
            <input type="password" id="password" name="password" required><br><br>
    <input type="submit" name="submit" value="Se connecter">
</form>
FORM;
        }elseif($this->http_method === 'POST'){
            // On nettoie l'username, mais pas le mot de passe car il sera hashé dans tous les cas
            $nomUser = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
            try {
                Auth::authenticate($nomUser, $_POST['password']);
                $contenu_html .= "<h4>Connexion réussie $nomUser !</h4>";
            } catch (AuthException $e) {
                $contenu_html .= $e->getMessage();
            }
        }
        return $contenu_html;
    }
}