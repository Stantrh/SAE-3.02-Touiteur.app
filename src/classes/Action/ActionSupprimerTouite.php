<?php

namespace touiteur\Action;

use touiteur\Database\SupprimerTouite;

class ActionSupprimerTouite extends Action
{


    public function execute(): string
    {

        try {
            SupprimerTouite::supprimerTouite($_GET["id-touite-supprimer"]);
            $retour="Le touite à été supprimé";
        }catch (\Exception $e){
            $retour="Vous ne pouvez pas supprimer ce touite";
        }

        return($retour);
    }
}