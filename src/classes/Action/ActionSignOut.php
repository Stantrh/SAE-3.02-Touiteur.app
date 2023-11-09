<?php

namespace touiteur\Action;

class ActionSignOut extends Action
{
    public function execute(): string
    {
        $user = unserialize($_SESSION['user']);
        $nomUser = $user->__get('nomUser');
        session_destroy();

        // Redirection vers la page d'accueil avec l'action par défaut
        header('Location: ?action=default');  // Assure-toi que c'est le chemin correct vers ta page d'accueil
        exit;
    }
}