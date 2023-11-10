<?php

namespace touiteur\Action;

use touiteur\Database\ConnectionFactory;

class ActionAfficherTendances extends Action
{

    public function execute(): string
    {
        $dt = time();
        $retour = "<h4>Voici les tags les plus utilisés (condition : au moins 1 utilisation) au : " . date( "d/m/Y", $dt ) . ' à ' . date("H:i:s", $dt) . "</h4><br>";
        // On selectionnera la liste des utilisateurs qui sont suivis par le plus de personnes par ordre décroissant. (avoir les + influents en premier)
        $requete = <<<SQL
SELECT idTag, COUNT(idTag) as nbUsages FROM TAG2TOUITE GROUP BY idTag ORDER BY COUNT(idTag) DESC;

SQL;
        $res = ConnectionFactory::$db->prepare($requete);
        $res->execute();

        $i = 1;
        while($row = $res->fetch(\PDO::FETCH_ASSOC)){
            $reqTag = <<<SQL
SELECT * from TAG where idTag = ?
SQL;
            $resTag = ConnectionFactory::$db->prepare($reqTag);
            $idTag = $row['idTag'];
            $resTag->bindParam(1, $idTag);
            $resTag->execute();

            $tag = $resTag->fetch(\PDO::FETCH_ASSOC);

            $libelle = $tag['libelle'];
            $nbUsages = $row['nbUsages'];

            $retour .= <<<HTML
<p>$i) id : $idTag || libelle : $libelle || $nbUsages usage(s)</p><br><br> 
HTML;
            $i++;
        }
        return $retour;

    }
}