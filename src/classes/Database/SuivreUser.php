<?php

namespace touiteur\Database;

use touiteur\Exception\InvalidActionException;

class SuivreUser
{
    public static function suivreUser(int $id, int $idUser): void
    {
        $db = ConnectionFactory::$db;

        //vérification que l'on en suit pas déjà la personne
        $requeteVerfierSuivi = <<<END
                                    SELECT * FROM SUIVREUSER
                                    WHERE idUser = ? AND idUserSuivi = ?
                                END;

        $st = $db->prepare($requeteVerfierSuivi);
        $st->bindParam(1, $idUser);
        $st->bindParam(2, $id);
        $st->execute();
        $row = $st->fetch();

        // si on obtient aucune ligne alors on peut commencer à suivre la personne
        if(!$row){
            $requeteSuivre = <<<END
                            INSERT INTO SUIVREUSER
                            VALUES (?, ?)
                        END;

            $st = $db->prepare($requeteSuivre);
            $st->bindParam(1, $idUser);
            $st->bindParam(2, $id);
            $st->execute();
        }else{
            throw new InvalidActionException();
        }
    }
}