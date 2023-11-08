<?php

namespace touiteur\Database;

use PDO;

class ListeIdTouite
{
    public static function listeTouite(string $query, array $prepare): array
    {
        $listeId = array();//créer une liste qu'on vas remplir des ids des touites a afficher
        $db = ConnectionFactory::$db;

        $st = $db->prepare($query);
        $st->execute($prepare);
        $row = $st->fetch();


        if ($row) { //test si la requete n'est pas revenue vide


            $listeId[] = $row[0];
            while ($row = $st->fetch(PDO::FETCH_ASSOC)) {

                foreach ($row as $v) {
                    $listeId[] = ($v); // on parcour le resultat de la requette pour stocker les ids des touites

                }

            }

        }
        return $listeId;
    }
}