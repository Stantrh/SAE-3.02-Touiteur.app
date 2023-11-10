<?php

namespace touiteur\Action;

use touiteur\Database\ConnectionFactory;

class ActionAfficherInfluents extends Action
{

    public function execute(): string
    {
        $dt = time();
        $retour = "Voici les utilisateurs les plus influents au : " . date( "d/m/Y", $dt ) . ' à ' . date("H:i:s", $dt);
        // On selectionnera la liste des utilisateurs qui sont suivis par le plus de personnes par ordre décroissant. (avoir les + influents en premier)
        $requete = <<<SQL
select idUserSuivi, count(idUserSuivi) as nbAbonnes from SUIVREUSER group by (idUserSuivi) ORDER BY COUNT(idUserSuivi) DESC
SQL;

        $res = ConnectionFactory::$db->prepare($requete);
        $res->execute();

        $i = 1;

        while($row = $res->fetch(\PDO::FETCH_ASSOC)){
            $reqUser = <<<SQL
SELECT * from UTILISATEUR where idUser = ?
SQL;
            $resUser = ConnectionFactory::$db->prepare($reqUser);
            $idUser = $row['nbAbonnes'];
            $resUser->bindParam(1, $idUser);
            $resUser->execute();


            $nomUser = $resUser->fetch(\PDO::FETCH_ASSOC)['nomUser'];
            $nbAbonnes = $row['nbAbonnes'];

            $retour .= <<<HTML
<p>$i) $nomUser : $nbAbonnes abonnés</p><br>
HTML;
            $i++;

        }
        return $retour;

    }
}