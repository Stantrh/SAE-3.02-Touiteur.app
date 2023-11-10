<?php

namespace touiteur\Action;

use touiteur\Database\ConnectionFactory;

class ActionAfficherInfluents extends Action
{

    public function execute(): string
    {
        $dt = time();
        $retour = "<h4>Voici les utilisateurs les plus influents (condition : au moins 1 abonné) au : " . date( "d/m/Y", $dt ) . ' à ' . date("H:i:s", $dt) . "</h4><br>";
        // On selectionnera la liste des utilisateurs qui sont suivis par le plus de personnes par ordre décroissant. (avoir les + influents en premier)
        $requete = <<<SQL
select distinct idUserSuivi, count(idUserSuivi) as nbAbonnes from SUIVREUSER group by (idUserSuivi) ORDER BY COUNT(idUserSuivi) DESC
SQL;

        $res = ConnectionFactory::$db->prepare($requete);
        $res->execute();

        $i = 1;

        while($row = $res->fetch(\PDO::FETCH_ASSOC)){
            $reqUser = <<<SQL
SELECT * from UTILISATEUR where idUser = ?
SQL;
            $resUser = ConnectionFactory::$db->prepare($reqUser);
            $idUser = $row['idUserSuivi'];
            $resUser->bindParam(1, $idUser);
            $resUser->execute();

            $user = $resUser->fetch(\PDO::FETCH_ASSOC);

            $idUserSuivi = $user['idUser'];
            $nomUser = $user['nomUser'];
            $nom = $user['nom'];
            $prenom = $user['prenom'];
            $email = $user['email'];
            $role = $user['role'];
            $nbAbonnes = $row['nbAbonnes'];

            $retour .= <<<HTML
<p>$i) id : $idUserSuivi || username : $nomUser ||  nom : $nom ||  prenom : $prenom  ||  email : $email  ||   role : $role   ||  $nbAbonnes abonné(s)</p><br><br> 
HTML;
            $i++;
        }
        return $retour;

    }
}