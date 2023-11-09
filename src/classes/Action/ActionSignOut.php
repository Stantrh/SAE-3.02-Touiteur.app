<?php

namespace touiteur\Action;

class ActionSignOut extends Action
{


    public function execute(): string
    {
        $user = unserialize($_SESSION['user']);
        $nomUser = $user->__get('nomUser');
        session_destroy();
        return "<h4>Vous avez bien été déconnecté $nomUser !</h4>";


    }
}