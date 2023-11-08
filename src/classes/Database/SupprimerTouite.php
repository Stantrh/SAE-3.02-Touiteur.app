<?php

namespace touiteur\Database;

use touiteur\Auth\Auth;

class SupprimerTouite
{
    /**
     * ATTENTION AUCUNE VERIFICATION DANS CETTE FONCTION
     * @param int $id id du touite a supprimer
     * @return void
     */
    public static function supprimerTouite(int $id){
        $db = ConnectionFactory::$db;

        try {
            //todo remplacer la query
            $query="Select idUser from touite where idTouite=?";
            $st = $db->prepare($query);
            $st->execute([$id]);
            $row=$st->fetch();

            Auth::checkAccountOwner($row["idUser"]);



            $query = "DELETE FROM TOUITE WHERE idTouite = ?;";
            $st = $db->prepare($query);
            $st->execute([$id]);
        }catch (\Exception $e){

        }

    }
}