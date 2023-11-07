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
            $image = "";
            $descriptionimage = "";


            if ($row["idImage"] != null) { //si il y a une image on fait les requetes pour obtenir l'image
                //sql
                $query = "SELECT * FROM `IMAGE` WHERE idImage=?";
                $st = $db->prepare($query);
                $st->execute([$row["idImage"]]);
                $row1 = $st->fetch();

                $image = $row1["cheminFichier"]; //on stock les infos
                $descriptionimage = $row1["description"];
            }
            $profile = ProfileRenderer::render($row["idUser"]);

            // on construit le html du touite avec les differents éléments qu'on a récupéré
            $retour = "<div class='touite'>$profile

<p class ='corpsTouite'> {$row["texteTouite"]} </p>
<div class='score'> {$row["score"]}</div>

<img src=$image alt=$descriptionimage>
                 </div>";
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

            $texte = substr($row["texteTouite"], 0, self::LONGUEURTOUITECOURT); //pour l'affichage court on coupe a un certain nombre de charactère


            $profile = ProfileRenderer::render($row["idUser"]);

            // on construit le touite court
            $retour = "<div class='touiteCourt'>$profile

<p class ='corpsTouite'> $texte </p>

                 </div>";
        } else {
            $retour = "pas de touite avec cette id:" . $id;
        }
        return ($retour);
    }

}