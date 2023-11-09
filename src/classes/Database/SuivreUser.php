<?php

namespace touiteur\Database;

use touiteur\Auth\Auth;
use touiteur\Exception\InvalidActionException;

class SuivreUser
{

    public static function suivreUser(int $id, int $idUser): void
    {

        $db = ConnectionFactory::$db;

        try {

            //on récupère l'id de la personne à suivre grace à l'id de la publication
            $requeteObtenirIdASuivre = <<<END
                                            SELECT idUser from TOUITE
                                            WHERE idTouite = ?
                                          END;


            $st = $db->prepare($requeteObtenirIdASuivre);
            // on complète la requete SQL
            $st->bindParam(1, $id);
            // on exécute la requete
            $st->execute();
            // on récupère le résultat de la requete
            $row = $st->fetch();

            //on récupère l'id de la personne à suivre
            $idUserASuivre = $row[0];

            //on ne peut pas se suivre nous meme
            if($idUserASuivre == $idUser){
                throw new InvalidActionException();
            }

            $requeteSuivre = <<<END
                                INSERT INTO SUIVREUSER
                                VALUES (?, ?)
                            END;

            $st = $db->prepare($requeteSuivre);
            $st->bindParam(1, $idUser);
            $st->bindParam(2, $idUserASuivre);
            $st->execute();

        }catch (\Exception $e){
            print $e;
        }
    }
}