<?php

namespace touiteur\Action;

use PDO;
use touiteur\Database\ConnectionFactory;

use touiteur\Database\ListeIdTouite;
use touiteur\Database\User;
use touiteur\Renderer\ListeRenderer;
use touiteur\Renderer\TouiteRenderer;

class ActionAfficherListeTouite extends Action
{
    /** @var string $TAG constante pour l'option d'affichage,
     * affiche tout les touites contenant un tag dans le tableau $_GET
     */
    public const TAG = "tag";

    /** @var string $UTILISATEUR constante pour l'option d'affichage
     * affiche tout les touites d'un utilisateur dans le tableau $_GET
     */

    public const UTILISATEUR = "utilisateur";

    /** @var string $DEFAULT constante pour l'option d'affichage,
     * affiche tout les touites par ordre chronologique
     */

    public const DEFAULT = "default";

    /** @var string $option option d'affichage de l'objet, change l'affichage des listes */
    private string $option;

    public const PAGINER = "paginer";

    /**
     * @param string $option option d'affichage de l'objet, definis par les constante de classe
     */
    function __construct(string $option)
    {
        parent::__construct();
        $this->option = $option;

    }

    /**
     * @return string code html de la liste de touite correspondant a l'option d'affichage
     */
    function execute(): string
    {
        $retour = "";

        switch ($this->option) {    //en fonction de l'option choisie pendant la construction
            //les touites affichés seront differents
            case self::TAG:
                $retour .= $this->tag();
                break;
            case self::UTILISATEUR:
                $retour .= $this->utilisateur();
                break;
            case self::PAGINER:
                $retour.= $this->paginer();
                break;

            default:
                $retour .= $this->default();
                break;
        }
        return ($retour);
    }

    private const NBTOUITEMAX = 3;  //nombre de touite max par page paginé

    /**
     * pagine les touites de la requete sql et change les boutons en fonction de l'action passé en parametre
     * @param string $sql requete sql qui donne l'id des touite à afficher dans l'ordre
     * @param array $options options de la requette sql sous forme d'un tableau
     * @param string $action action GET qui redirige ver la page qu'on veut ex: pour les touites de l'user le parametre sera: "afficher-touite-user&user=x"
     * @return string affichage html
     */
    private function paginer(string $sql, array $options,string $action): string
    {
        $retour = "";
        $page = 0;
        $pageSuivante = 1;
        $pagePrecedente = 0;


        //fait le decalage du nombre de page en fonction de la page actuelle
        if (isset($_GET["page"]) && $_GET["page"] >= 0) {
            $page = $_GET["page"];
            $pageSuivante = $page + 1;
            $pagePrecedente = $page - 1;
        }

        /*
        if($sql[-1]=';'){
            $sql=substr($sql,0,1);
        }
*/

        //rajout des options pour paginer les touites
        $query=$sql." limit ? offset ?";
        //fusion des options pour la requete de base et des options pour la pagination
        $parametreRequete=array_merge($options,[self::NBTOUITEMAX, $page * self::NBTOUITEMAX]);

        //render des liste touites
        $resultat = ListeIdTouite::listeTouite($query, $parametreRequete);
        $retour = ListeRenderer::render($resultat, TouiteRenderer::COURT);


        $bouttonSuivant = <<<END
        href="?action=$action&page=$pageSuivante"
        END;

        //enleve le bouton suivant si il n'y a plus de touite a afficher après, a noter que le bouton suivant sera toujours affiché sur la dernière page si le nombre de touite total est multiple de NBTOUITEMAX
        if (count($resultat) < self::NBTOUITEMAX) {
            $bouttonSuivant = "";
        }


        $bouttonPrecedent = <<<END
        href="?action=$action&page=$pagePrecedente"
        END;

        //enleve le bouton suivant si il n'y a pas de page precedente
        if ($page === $pagePrecedente || $pagePrecedente < 0) {
            $bouttonPrecedent = "";
        }


        $retour .= <<<END
<div class="bouton-pagination">
<a class="boutton-paginer" id="bouton-precedent" $bouttonPrecedent>
        Page Precedente
        </a>
<a class="boutton-paginer" id="bouton-suivant" $bouttonSuivant>
        Page Suivante
        </a>


</div>


END;


        return ($retour);
    }

    /**
     * @return string liste des touites de l'user dans le GET
     */
    private function utilisateur(): string
    {
        $retour = "";

        //on verifie si il y a un parametre user dans le GET
        if (isset($_GET["user"])) {

            //select les idTouite de l'user dans le get
            $query = "SELECT idTouite FROM `TOUITE` where idUser= ? order by date desc";
            $option=[$_GET["user"]];
            //action qui nous a ammenée sur la page en premier lieu;
            $action="afficher-touite-user&user=$option[0]";

            $retour.= $this->paginer($query,$option,$action );

        } else {
            $retour = "Pas d'user avec cet id:" . $_GET["user"];
        }


        return ($retour);
    }

    /**
     * @return string code html d'une liste de touite du plus récent au plus vieux
     */
    private function default(): string
    {
            $retour = "";
            if(isset($_SESSION['user'])){
                // On fait une immense requête pour traiter chaque sous ensemble par date, puis encore tout l'ensemble
                $requeteGen = <<<SQL
SELECT idTouite 
FROM (
    SELECT TOUITE.idTouite as idTouite, TOUITE.date as dateTouite
    FROM SUIVREUSER, UTILISATEUR, TOUITE
    WHERE SUIVREUSER.idUser=UTILISATEUR.idUser 
        AND TOUITE.idUser=SUIVREUSER.idUserSuivi 
        AND UTILISATEUR.idUser=? 
    UNION
    SELECT DISTINCT TOUITE.idTouite as idTouite, TOUITE.date as dateTouite
    FROM SUIVRETAG, TAG2TOUITE, TOUITE, UTILISATEUR
    WHERE SUIVRETAG.idUser=UTILISATEUR.idUser 
        AND TAG2TOUITE.idTag=SUIVRETAG.idTag 
        AND TOUITE.idTouite=TAG2TOUITE.idTouite 
        AND UTILISATEUR.idUser=?
)as listeTouites 
ORDER BY dateTouite DESC
SQL;
                //$res = ConnectionFactory::$db->prepare($requeteGen);
                $action="";
                $idUserCourant = User::getIdSession();
                $retour=$this->paginer($requeteGen,[$idUserCourant,$idUserCourant],$action);
                /*$res->bindParam(1, $idUserCourant);
                $res->bindParam(2, $idUserCourant);
                $res->execute();

                $resultat = [];
                while($row = $res->fetch(PDO::FETCH_ASSOC)){
                    $resultat[] = $row['idTouite'];
                }
                */


        }else{
                //requete sql qui vas selectionner les idTouite par ordre decroissant sur la date
                $query = "SELECT idTouite FROM `TOUITE` order by date desc";

                $retour=$this->paginer($query,[],"");


                //$resultat = ListeIdTouite::listeTouite($query, []); //sous traite la requete a une autre classe


            }


        //$retour .= ListeRenderer::render($resultat, TouiteRenderer::COURT); //on fait le rendu html de la liste
        return ($retour);

    }

    /**
     * @return string liste de touite correspondant au tag en GET
     */
    private function tag(): string
    {
        $retour = "";


        //requete sql qui vas selectionner les idTouite par ordre decroissant sur la date
        $query = "SELECT TOUITE.idTouite FROM `TOUITE`,`TAG2TOUITE`,`TAG` 
                WHERE TOUITE.idTouite=TAG2TOUITE.idTouite 
                  and TAG.idTag=TAG2TOUITE.idTag and TAG.libelle=?";

        $tag = "#" . $_GET["tag"];

        $action="afficher-liste-tag&tag=$tag";

        /*
        $resultat = ListeIdTouite::listeTouite($query, [$tag]); //sous traite la requete a une autre classe

        $retour .= ListeRenderer::render($resultat, TouiteRenderer::COURT); //on fait le rendu html de la liste de touite correspondant au ids données
        */
        $retour=$this->paginer($query,[$tag],$action);

        return ($retour);
    }


}