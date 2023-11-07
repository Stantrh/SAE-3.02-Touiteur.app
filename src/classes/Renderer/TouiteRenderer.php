<?php

namespace touiteur\Renderer;

use PDO;
use touiteur\Connection\ConnectionFactory;

class TouiteRenderer
{
const LONG="long";
const COURT="court";
    public static function render(int $id, string $option):string{
        $retour="";
        switch ($option){
            case self::LONG:
                $retour=self::renderLong($id);
                break;
            case self::COURT:
                $retour=self::renderCourt();
                break;

            default:
                $retour=self::renderCourt();
                break;


        }
        return($retour);
    }
    private static function renderLong(int $id):string{
        $retour="";
        $db = ConnectionFactory::$db;
        $query = "SELECT * FROM `TOUITE` WHERE idTouite=?";
        $st = $db->prepare($query);
        $st->execute([$id]);
        $row = $st->fetch();
        if($row){
            $image="";
            $descriptionimage="";
            if($row["idImage"]!=null){
                $query = "SELECT * FROM `IMAGE` WHERE idImage=?";
                $st = $db->prepare($query);
                $st->execute([$row["idImage"]]);
                $row1 = $st->fetch();
                $image=$row1["cheminFichier"];
                $descriptionimage=$row1["description"];
            }
            $profile=ProfileRenderer::render($row["idUser"]);

            $retour="<div class='touite'>$profile

<p class ='corpsTouite'> {$row["texteTouite"]} </p>
<div class='score'> {$row["score"]}</div>

<img src=$image alt=$descriptionimage>
                 </div>";
        }else{
            $retour="pas de touite avec cette id:".$id;
        }
        return($retour);

   





    }

    private static function renderCourt($id):string{
        $retour="";
        $retour="";
        $db = ConnectionFactory::$db;
        $query = "SELECT * FROM `TOUITE` WHERE idTouite=?";
        $st = $db->prepare($query);
        $st->execute([$id]);
        $row = $st->fetch();
        if($row){

            $profile=ProfileRenderer::render($row["idUser"]);

            $retour="<div class='touiteCourt'>$profile

<p class ='corpsTouite'> substr({$row["texteTouite"]},0,10) </p>

                 </div>";
        }else{
            $retour="pas de touite avec cette id:".$id;
        }
        return($retour);
    }

}