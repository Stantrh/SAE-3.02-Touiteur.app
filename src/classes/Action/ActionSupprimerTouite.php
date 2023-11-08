<?php

namespace touiteur\Action;

use touiteur\Database\SupprimerTouite;

class ActionSupprimerTouite extends Action
{


    public function execute(): string
    {
        $retour = "Aucune action  ";//.$_GET["id-touite-supprimer"]."  ".gettype($_GET["id-touite-supprimer"])=="integer";
        if (isset($_GET["id-touite-supprimer"])) {
            try {
                SupprimerTouite::supprimerTouite((int)($_GET["id-touite-supprimer"]));
                $retour = "Le touite à été supprimé";

            } catch (\Exception $e) {
                $retour = "Vous ne pouvez pas supprimer ce touite";
            }
        }

        return ($retour);
    }
}