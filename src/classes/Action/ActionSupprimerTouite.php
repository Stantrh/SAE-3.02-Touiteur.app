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
                $retour = "Le touite a bien été supprimé";
                $retour .= <<<HTML
<p>Retourner à l'accueil : 
<p><a href="?action=default">Retourner</a></p>
</p>
HTML;
            } catch (\Exception $e) {
                $retour = "Vous ne pouvez pas supprimer ce touite";
                $retour .= $e->getMessage();
            }
        }

        return ($retour);
    }
}