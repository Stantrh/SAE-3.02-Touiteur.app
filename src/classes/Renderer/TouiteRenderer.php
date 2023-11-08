<?php

namespace touiteur\Renderer;

use PDO;
use touiteur\Database\ConnectionFactory;


class TouiteRenderer
{
    const LONGUEURTOUITECOURT = 50;
    const LONG = "long";
    const COURT = "court";

    /**
     * @param int $id id du touite a faire le rendu
     * @param string $option option de rendue du touite, const de classe TouiteRenderer
     * @return string  touite en html
     */

    public static function render(int $id, string $option): string
    {
        $retour = "";
        switch ($option) {
            case self::LONG:
                $retour = self::renderLong($id);
                break;
            case self::COURT:
                $retour = self::renderCourt($id);
                break;

            default:
                $retour = self::renderCourt($id);
                break;


        }
        return ($retour);
    }


    /**
     * @param int $id id du touite a rendre
     * @return string
     */
    private static function renderLong(int $id): string
    {
        $retour = "";


        //sql
        $db = ConnectionFactory::$db;
        $query = "SELECT * FROM `TOUITE` WHERE idTouite=?";
        $st = $db->prepare($query);
        $st->execute([$id]);
        $row = $st->fetch();

        if ($row) {     //verification de l'existance du touite

            $htmlImage = "";
            if ($row["idImage"] != null) { //si il y a une image on fait les requetes pour obtenir l'image
                //sql
                $query = "SELECT * FROM `IMAGE` WHERE idImage=?";
                $st = $db->prepare($query);
                $st->execute([$row["idImage"]]);
                $row1 = $st->fetch();

                $image = $row1["cheminFichier"]; //on stock les infos
                $descriptionimage = $row1["description"];
                $htmlImage = "<img src=$image alt=$descriptionimage>";
            }
            $profile = ProfileRenderer::render($row["idUser"]);

            // on construit le html du touite avec les differents éléments qu'on a récupéré
            $retour = <<<END
<div class='touite'>\n
$profile

    <p class ='corpsTouite-long' > {$row["texteTouite"]} </p>


     $htmlImage
     
         <div class='score'>
        <span id="score">Score : <span id="scoreValue"></span></span>
        <button id="likeButton">Like</button>
        <button id="dislikeButton">Dislike</button>
    </div>
</div><br>
END;
        } else {
            $retour = "pas de touite avec cette id:" . $id;
        }
        return ($retour);

    }

    /**
     * @param int $id id du touite a rendre
     * @return string
     */
    private static function renderCourt($id): string
    {
        $retour = "";
        //sql
        $db = ConnectionFactory::$db;
        $query = "SELECT * FROM `TOUITE` WHERE idTouite=?";
        $st = $db->prepare($query);
        $st->execute([$id]);
        $row = $st->fetch();

        if ($row) {

            $texte = substr($row["texteTouite"], 0, self::LONGUEURTOUITECOURT)."..."; //pour l'affichage court on coupe a un certain nombre de charactère

            //action qui doit s'executer quand on clique sur le texte du touite, ici on affiche le touite en detail
            $actionCliqueTouite = "?action=afficher-touite-detail&id-touite=$id";
            $profile = ProfileRenderer::render($row["idUser"]);

            // on construit le touite court
            $retour .= <<<END
            <div class='touiteCourt'>
                    $profile
                    <a href="$actionCliqueTouite" class='touite-clickable'>
                        <p class ='corpsTouite'> $texte </p>
                    </a>
            </div>\n
END;
        } else {
            $retour = "pas de touite avec cette id:" . $id;
        }
        return ($retour);
    }

}