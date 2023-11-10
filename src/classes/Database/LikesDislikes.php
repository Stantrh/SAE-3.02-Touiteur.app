<?php

namespace touiteur\Database;

/**
 * Permet de gérer au niveau base de donnée l'insertion des likes/dislikes et update du score
 */
class LikesDislikes
{

    /**
     * @param $postId
     * @param $idUser
     * @return void
     */
    public static function toggleLike($postId, $idUser) : void{
        // Vérifie d'abord si l'utilisateur a déjà liké ce post
        $dejaLike = <<<SQL
SELECT * FROM LIKE2TOUITE WHERE idUser = ? AND idTouite = ?
SQL;
        $count = ConnectionFactory::$db->prepare($dejaLike);
        $count->bindParam(1, $idUser);
        $count->bindParam(2, $postId);
        $count->execute();

        if ($count->rowCount() === 0) {
            // Si l'user n'a pas deja like on l'ajoute
            $ajout = <<<SQL
INSERT INTO LIKE2TOUITE (idUser, idTouite, appreciation) VALUES(?, ?, 1)
SQL;
            $res = ConnectionFactory::$db->prepare($ajout);
        } else {
            // Si l'user a déjà like on le supprime de la table
            $suppr = <<<SQL
DELETE FROM LIKE2TOUITE WHERE idUser = ? AND idTouite = ?
SQL;
            $res = ConnectionFactory::$db->prepare($suppr);
        }
        $res->bindParam(1, $idUser);
        $res->bindParam(2, $postId);
        $res->execute();


        // Mettre à jour la table TOUITE
        $majScore = <<<SQL
UPDATE TOUITE set score = score + ? where idTouite = ?
SQL;
        $res2 = ConnectionFactory::$db->prepare($majScore);
        $val = -1; // s'il etait déjà dans la base on remove son like
        if($count->rowCount() === 0){ // s'il etait pas dans la table alors il vient de liker
            $val = 1;
        }
        $res2->bindParam(1, $val);
        $res2->bindParam(2, $postId);
        $res2->execute();
    }

    /**
     * @param $postId
     * @param $idUser
     * @return void
     */
    public static function toggleDislike($postId, $idUser) :void {
        // On vérifie s'il a déjà dislike le touite
        $dejaDislike = <<<SQL
SELECT * FROM LIKE2TOUITE WHERE idUser = ? AND idTouite = ?
SQL;
        $count = ConnectionFactory::$db->prepare($dejaDislike);
        $count->bindParam(1, $idUser);
        $count->bindParam(2, $postId);
        $count->execute();

        if ($count->rowCount() === 0) {
            // S'il a pas dislike
            $ajout = <<<SQL
INSERT INTO LIKE2TOUITE (idUser, idTouite, appreciation) VALUES(?, ?, -1)
SQL;
            $res = ConnectionFactory::$db->prepare($ajout);
        } else {
            // Si l'utilisateur a deja dislike
            $suppr = <<<SQL
DELETE FROM LIKE2TOUITE WHERE idUser = ? AND idTouite = ?
SQL;
            $res = ConnectionFactory::$db->prepare($suppr);
        }

        // Dans les deux cas, on lie les paramètres et exécute la requête
        $res->bindParam(1, $idUser);
        $res->bindParam(2, $postId);
        $res->execute();

        // Mettre à jour la table TOUITE
        $majScore = <<<SQL
UPDATE TOUITE set score = score + ? where idTouite = ?
SQL;
        // s'il a deja dislike (on augmente de 1)
        $var = 1;
        if($count->rowCount() === 0){ // sinon on diminue de 1
            $var = -1;
        }
        $res2 = ConnectionFactory::$db->prepare($majScore);
        $res2->bindParam(1, $var);
        $res2->bindParam(2, $postId);
        $res2->execute();
    }

// Fonction pour récupérer le score actuel du post
    public static function getPostScore($postId) : int{
        $requete = <<<SQL
select score from TOUITE where idTouite = ?
SQL;
        $res = ConnectionFactory::$db->prepare($requete);
        $res->bindParam(1, $postId);
        $res->execute();

        return $res->fetchColumn();
    }
}