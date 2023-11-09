<?php

namespace touiteur\Database;

class SuivreTag
{

    /**
     * Il faut verifier l'id de l'utilisateur avant d'utiliser cette fonction
     * Pas besion de verifier le tag
     * @param string $libelle tag a suivre
     * @param int $idUser id de l'user qui suit le tag
     * @return void
     */
    public static function suivreTag(string $libelle, int $idUser)
    {
        $db = ConnectionFactory::$db;

        $requeteObtenirTagASuivre = <<<END
        SELECT idTag FROM `TAG` WHERE TAG.libelle=?
        END;

        $st = $db->prepare($requeteObtenirTagASuivre);
        // on complète la requete SQL
        $st->bindParam(1, $libelle);
        // on exécute la requete

        $st->execute();


        // on récupère le résultat de la requete
        $row = $st->fetch();

        //si le tag existe le tableau devrait avoir une valeur dedant
        if ($row) {
            $idTag=$row[0];
            $requeteSuivreTag = <<<END
INSERT INTO `SUIVRETAG` (idTag,idUser) VALUES (?,?)
END;

            $st = $db->prepare($requeteSuivreTag);
            // on complète la requete SQL

            try {
                $st->execute([$idTag,$idUser]);

            } catch (\Exception $exception) {
                throw new \Exception("Tag déjà suivi");
            }
            // on exécute la requete


        } else {
            throw new \Exception("Ce tag n'existe pas");
        }


    }

}