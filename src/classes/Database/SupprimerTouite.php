<?php

namespace touiteur\Database;

use touiteur\Auth\Auth;

class SupprimerTouite
{
    /**
     * @param int $id id du touite a supprimer
     * @return void
     */
    public static function supprimerTouite(int $id){
        $db = ConnectionFactory::$db;

        try {

            $query="Select idUser from `TOUITE` where idTouite=?";
            $st = $db->prepare($query);
            $st->execute([$id]);
            $row=$st->fetch();

            Auth::checkAccountOwner($row["idUser"]);



            $query = "DELETE FROM `TOUITE` WHERE idTouite = ?;";
            $st = $db->prepare($query);
            $st->execute([$id]);
        }catch (\Exception $e){
            throw $e;
        }

    }
}