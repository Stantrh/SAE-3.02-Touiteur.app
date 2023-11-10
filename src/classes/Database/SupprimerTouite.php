<?php

namespace touiteur\Database;

use touiteur\Auth\Auth;

class SupprimerTouite
{
    /**
     * @param int $id id du touite a supprimer
     * @return void
     */
    public static function supprimerTouite(int $id)
    {
        $db = ConnectionFactory::$db;

        try {
            $query = "Select idUser,idImage from `TOUITE` where idTouite=?";
            $st = $db->prepare($query);
            $st->execute([$id]);
            $row = $st->fetch();
            //check si le row existe
            if($row){
            Auth::checkAccountOwner($row["idUser"]);
            }

            $query = "select IMAGE.cheminFichier from IMAGE,TOUITE where IMAGE.idImage=TOUITE.idImage and TOUITE.idTouite=?";
            $st = $db->prepare($query);
            $st->execute([$id]);
            $row1 = $st->fetch();
            $cheminImage = null;
            if($row1 != null){
                $cheminImage = $row1[0];
            }

            $query = "DELETE FROM `TAG2TOUITE` WHERE idTouite = ?;";
            $st = $db->prepare($query);
            $st->execute([$id]);

            $query = "DELETE FROM `LIKE2TOUITE` WHERE idTouite = ?;";
            $st = $db->prepare($query);
            $st->execute([$id]);


            $query = "DELETE FROM `TOUITE` WHERE idTouite = ?;";
            $st = $db->prepare($query);
            $st->execute([$id]);

            $query = "DELETE FROM IMAGE where idImage=?";
            $st = $db->prepare($query);
            if($row){
            $st->execute([$row["idImage"]]);
            }


            if($cheminImage != null){
                try {
                    if(!unlink($cheminImage)){
                        echo("le fichier n'a pa été supprimé du serveur");
                    }
                }catch (\Exception $e ){
                    echo("le fichier n'a pa été supprimé du serveur: "+$e->getMessage());

                }


            }

        } catch (\Exception $e) {
            echo $e->getMessage();
        }

    }
}