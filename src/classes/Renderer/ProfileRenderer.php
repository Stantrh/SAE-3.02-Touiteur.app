<?php

namespace touiteur\Renderer;

use touiteur\Connection\ConnectionFactory;

class ProfileRenderer
{
    public static function render(int $id):string{
        $retour="";
        $db = ConnectionFactory::$db;
        $query = "SELECT * FROM `UTILISATEUR` WHERE idUser=?";
        $st = $db->prepare($query);
        $st->execute([$id]);
        $row = $st->fetch();
        if($row){
            $retour.="<div class='user'>".$row["nom"]." ".$row["prenom"]."</div>";
        }else{
            $retour="Pas d'utilisateur correspondant a l'id:".$id;
        }
        return $retour;
    }
}