<?php

namespace touiteur\Database;

use PDO;

/**
 * Permet de gérer au niveau base de donnée l'insertion des likes/dislikes et update du score
 */
class LikesDislikes
{

    /**
     * @param $idTouite
     * @param $idUser
     * @return void
     */
    public static function toggleLike($idTouite, $idUser) : void{
        // Vérifie d'abord si l'utilisateur est deja dans la table (donc soit a deja liké ou deja dislike)
        $dejaLike = <<<SQL
SELECT * FROM LIKE2TOUITE WHERE idUser = ? AND idTouite = ?
SQL;
        $count = ConnectionFactory::$db->prepare($dejaLike);
        $count->bindParam(1, $idUser);
        $count->bindParam(2, $idTouite);
        $count->execute();


        // Si l'user n'a pas deja like/dislike on l'ajoute
        if ($count->rowCount() === 0) {
            $ajout = <<<SQL
INSERT INTO LIKE2TOUITE (idUser, idTouite, appreciation) VALUES(?, ?, 1)
SQL;
            $val = 1;
            $res = ConnectionFactory::$db->prepare($ajout);
            $res->bindParam(1, $idUser);
            $res->bindParam(2, $idTouite);
            $res->execute();
        } else {
            // On stocke la valeur de l'appreciation
            $appreciation = (int) $count->fetch(PDO::FETCH_ASSOC)['appreciation'];


            // Ici on vérifie s'il a déjà like/dislike, alors on supprime le like/dislike
            // S'il a deja dislike, il faut supprimer le dislike aussi, mais après insérer le like
            // Donc on peut supprimer la ligne où il apparait dans tous les cas
            $suppr = <<<SQL
DELETE FROM LIKE2TOUITE WHERE idUser = ? AND idTouite = ?
SQL;
            $resExistant = ConnectionFactory::$db->prepare($suppr);
            $resExistant->bindParam(1, $idUser);
            $resExistant->bindParam(2, $idTouite);
            $resExistant->execute();
            $val = -1; // valeur qu'on incrémente au score si l'utilisateur avait déjà liké


            if($appreciation === -1){ // Si l'utilisateur  avait dislike on incrémente le score de 2
                $insertionLike = <<<SQL
insert into LIKE2TOUITE(idUser, idTouite, appreciation) VALUES (?, ?, 1)
SQL;
                $val = 2; // valeur qu'on incrémente au score si l'utilisateur avait deja disliké, ainsi ça fait +1 +1
                $res3 = ConnectionFactory::$db->prepare($insertionLike);
                $res3->bindParam(1, $idUser);
                $res3->bindParam(2, $idTouite);
                $res3->execute();
            }
        }

        // Mettre à jour la table TOUITE
        $majScore = <<<SQL
UPDATE TOUITE set score = score + ? where idTouite = ?
SQL;
        $res2 = ConnectionFactory::$db->prepare($majScore);

        $res2->bindParam(1, $val);
        $res2->bindParam(2, $idTouite);
        $res2->execute();
    }

    /**
     * Permet de toggle un dislike, selon si le touite a déjà été liké, disliké ou pas encore
     * Il update évidemment son score
     * @param $idTouite
     * @param $idUser
     * @return void
     */
    public static function toggleDislike($idTouite, $idUser) :void {
        // Vérifie d'abord si l'utilisateur est deja dans la table (donc soit a deja liké ou deja dislike)
        $dejaDislike = <<<SQL
SELECT * FROM LIKE2TOUITE WHERE idUser = ? AND idTouite = ?
SQL;
        $count = ConnectionFactory::$db->prepare($dejaDislike);
        $count->bindParam(1, $idUser);
        $count->bindParam(2, $idTouite);
        $count->execute();


        // Si l'user n'a pas deja like/dislike on ajoute son dislike
        if ($count->rowCount() === 0) {
            $ajout = <<<SQL
INSERT INTO LIKE2TOUITE (idUser, idTouite, appreciation) VALUES(?, ?, -1)
SQL;
            $val = -1;
            $res = ConnectionFactory::$db->prepare($ajout);
            $res->bindParam(1, $idUser);
            $res->bindParam(2, $idTouite);
            $res->execute();
        } else {
            // On stocke la valeur de l'appreciation
            $appreciation = (int) $count->fetch(PDO::FETCH_ASSOC)['appreciation'];


            // Ici on vérifie s'il a déjà like/dislike, alors on supprime le like/dislike
            // S'il a deja dislike, il faut supprimer le dislike aussi, mais après insérer le like
            // Donc on peut supprimer la ligne où il apparait dans tous les cas
            $suppr = <<<SQL
DELETE FROM LIKE2TOUITE WHERE idUser = ? AND idTouite = ?
SQL;
            $resExistant = ConnectionFactory::$db->prepare($suppr);
            $resExistant->bindParam(1, $idUser);
            $resExistant->bindParam(2, $idTouite);
            $resExistant->execute();
            $val = 1; // valeur qu'on incrémente au score si l'utilisateur avait déjà disliké


            if($appreciation === 1){ // Si l'utilisateur  avait like on décremente le score de 2
                $insertionLike = <<<SQL
insert into LIKE2TOUITE(idUser, idTouite, appreciation) VALUES (?, ?, -1)
SQL;
                $val = -2; // valeur qu'on incrémente au score si l'utilisateur avait deja disliké, ainsi ça fait +1 +1
                $res3 = ConnectionFactory::$db->prepare($insertionLike);
                $res3->bindParam(1, $idUser);
                $res3->bindParam(2, $idTouite);
                $res3->execute();
            }
        }

        // Mettre à jour la table TOUITE
        $majScore = <<<SQL
UPDATE TOUITE set score = score + ? where idTouite = ?
SQL;
        $res2 = ConnectionFactory::$db->prepare($majScore);

        $res2->bindParam(1, $val);
        $res2->bindParam(2, $idTouite);
        $res2->execute();
    }


    /**
     * Renvoie le score actuel du touite
     * Le script js l'utilise
     * @param $idTouite
     * @return int
     */
    public static function getPostScore($idTouite) : int{
        $requete = <<<SQL
select score from TOUITE where idTouite = ?
SQL;
        $res = ConnectionFactory::$db->prepare($requete);
        $res->bindParam(1, $idTouite);
        $res->execute();

        return $res->fetchColumn();
    }
}