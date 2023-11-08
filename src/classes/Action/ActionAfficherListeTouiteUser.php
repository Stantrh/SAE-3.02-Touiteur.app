<?php

namespace touiteur\Action;

use PDO;
use touiteur\Database\ConnectionFactory;
use touiteur\Database\ListeIdTouite;
use touiteur\Renderer\ListeRenderer;
use touiteur\Renderer\TouiteRenderer;

class ActionAfficherListeTouiteUser extends Action
{

    public function execute(): string
    {
        $retour = "";

        if (isset($_GET["user"])) {

            $query = "SELECT idTouite FROM `TOUITE` where idUser= ? order by date desc";

            $listeId = ListeIdTouite::listeTouite($query, [$_GET["user"]]);

            $retour .= ListeRenderer::render($listeId, TouiteRenderer::COURT); //on fait le rendu html de la liste de touite correspondant au ids données


        } else {
            $retour = "Pas d'user avec cet id:" . $_GET["user"];
        }



        return ($retour);
    }
}