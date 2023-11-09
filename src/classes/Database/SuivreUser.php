<?php

namespace touiteur\Database;

class SuivreUser
{
    public static function suivreUser(int $id, int $idUser): void
    {
        $db = ConnectionFactory::$db;
        try {
            $requeteSuivre = <<<END
                                INSERT INTO SUIVREUSER
                                VALUES (?, ?)
                            END;

            $st = $db->prepare($requeteSuivre);
            $st->bindParam(1, $idUser);
            $st->bindParam(2, $id);
            $st->execute();

        }catch (\Exception $e){
            print $e;
        }
    }
}