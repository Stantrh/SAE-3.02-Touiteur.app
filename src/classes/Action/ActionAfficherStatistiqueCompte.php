<?php

namespace touiteur\Action;

use PDO;
use touiteur\Database\ConnectionFactory;
use touiteur\Database\User;
use touiteur\Renderer\ProfileRenderer;

class ActionAfficherStatistiqueCompte extends Action
{

    public function execute(): string
    {
        $idUser= User::getIdSession();//todo remplacer;
        $scoreMoyenTouite=self::scoreMoyenUser($idUser);
        $listeIdAfficher=self::listeFollower($idUser);
        $retour = <<<END
<div class="score-moyen-touite-container">
    <p class="texte-score-moyen-touite">Le score moyen de vos touite est: </p> 
    <p class="score-moyen-touite">$scoreMoyenTouite</p>
</div>
<div class="liste-followers">
END;

        foreach ($listeIdAfficher as $item) {
            $html=ProfileRenderer::render($item);
            $retour.=$html;
        }

        return ($retour."</div>");
    }

    /**
     * @param int $idUser id de l'user don on veut avoir la liste de follower
     * @return array   liste d'id des followers
     */
    private static function listeFollower(int $idUser): array
    {
        $retour = array();

        $requeteFollowers = "select UTILISATEUR.idUser as id from SUIVREUSER,UTILISATEUR 
                           where UTILISATEUR.idUser=SUIVREUSER.idUser 
                             and SUIVREUSER.idUserSuivi=?";

        $db = ConnectionFactory::$db;
        $st = $db->prepare($requeteFollowers);
        $st->execute([$idUser]);
        $row = $st->fetch();
        do {
            $retour[]= $row["id"];

        } while ($row = $st->fetch(PDO::FETCH_ASSOC));


        return ($retour);
    }

    /**
     * @param int $idUser id de l'user dont on veut avoir le score moyen des touites
     * @return int score moyen des touites de l'user
     */
    private static function scoreMoyenUser(int $idUser): float
    {
        $scoreMoyen = 0;
        $nbTouite = 0;

        //donne le score par touite de l'utilisateur en parametre
        $requeteLikeParTouite = "select TOUITE.idTouite,SUM(LIKE2TOUITE.appreciation) as moyen
                                from TOUITE,LIKE2TOUITE where 
                                TOUITE.idTouite=LIKE2TOUITE.idTouite and 
                                TOUITE.idUser=? GROUP BY TOUITE.idTouite ";
        $db = ConnectionFactory::$db;
        $st = $db->prepare($requeteLikeParTouite);
        $st->execute([$idUser]);
        $row = $st->fetch();


        if ($row) {
            //on fait la moyenne du score des touites
            do {
                $scoreMoyen += $row["moyen"];
                $nbTouite++;
            } while ($row = $st->fetch(PDO::FETCH_ASSOC));

            $scoreMoyen = round($scoreMoyen / $nbTouite, 2);
        }

        return ($scoreMoyen);
    }
}