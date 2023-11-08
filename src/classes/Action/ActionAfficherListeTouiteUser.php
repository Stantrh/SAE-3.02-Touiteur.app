<?php

namespace touiteur\Action;

use PDO;
use touiteur\Database\ConnectionFactory;
use touiteur\Renderer\ListeRenderer;
use touiteur\Renderer\TouiteRenderer;

class ActionAfficherListeTouiteUser extends Action
{

    public function execute(): string
    {
        $retour ="";

        if(isset($_GET["user"])){
            $db = ConnectionFactory::$db;
            $query = "SELECT idTouite FROM `TOUITE` where idUser= ? order by date desc";
            $st = $db->prepare($query);
            $st->execute([$_GET["user"]]);
            $row = $st->fetch();
            if($row){

                $listeId = array();     //créer une liste qu'on vas remplir des ids des touites a afficher
                $listeId[] = $row[0];
                while ($row = $st->fetch(PDO::FETCH_ASSOC)) {

                    foreach ($row as $v) {
                        $listeId[] = ($v); // on parcour le resultat de la requette pour stocker les ids des touites

                    }

                }

                $retour .= ListeRenderer::render($listeId, TouiteRenderer::COURT); //on fait le rendu html de la liste de touite correspondant au ids données


            }else{
                $retour="Cet user n'a pas encore de touite";
            }

        }else{
            $retour="Pas d'user avec cet id:".$_GET["user"];
        }

        return($retour);
    }
}