<?php

namespace touiteur\Database;

use PDO;

class ListeIdTouite
{

    /**
     * prend une requette sql et renvoie une liste d'id, a utiliser de pair avec ListeRenderer
     * @param string $query requette sql à executer, DOIT OBLIGATOIREMENT RENDRE UNE LISTE D'idTouite
     * @param array $prepare options pour la préparation de requette DOIT ETRE UN TABLEAU
     * @return array array d'id de touite correspondant a la requette
     */
    public static function listeTouite(string $query, array $prepare): array
    {
        $listeId = array(); //créer une liste qu'on vas remplir des ids des touites correspondant a la requette

        //requette sql
        $db = ConnectionFactory::$db;
        $st = $db->prepare($query);
        $st->execute($prepare);
        $row = $st->fetch();


        if ($row) { //test si la requete n'est pas revenue vide
            //on met le premier element dans la liste

            do {
                $listeId[] = ($row[0]); // on parcour le resultat de la requette pour stocker les ids des touites
            } while ($row = $st->fetch(PDO::FETCH_ASSOC));

        }
        return $listeId;
    }
}