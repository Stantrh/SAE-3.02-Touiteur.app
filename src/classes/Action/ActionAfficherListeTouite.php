<?php

namespace touiteur\Action;

use PDO;
use touiteur\Database\ConnectionFactory;

use touiteur\Database\ListeIdTouite;
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
     * @return string liste de touite paginer en fonction de l'option page dans le $_GET
     */
    private function paginer(): string
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
        //requete et render de la liste de touite
        $query = "select idTouite from `TOUITE` order by date desc limit ? offset ?";
        $resultat = ListeIdTouite::listeTouite($query, [self::NBTOUITEMAX, $page * self::NBTOUITEMAX]);
        $retour = ListeRenderer::render($resultat, TouiteRenderer::COURT);


        $bouttonSuivant = <<<END
        href="?action=afficher-liste-touite-paginer&page=$pageSuivante"
        END;

        //enleve le bouton suivant si il n'y a plus de touite a afficher après, a noter que le bouton suivant sera toujours affiché sur la dernière page si le nombre de touite total est multiple de NBTOUITEMAX
        if (count($resultat) < self::NBTOUITEMAX) {
            $bouttonSuivant = "";
        }


        $bouttonPrecedent = <<<END
        href="?action=afficher-liste-touite-paginer&page=$pagePrecedente"
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

            $query = "SELECT idTouite FROM `TOUITE` where idUser= ? order by date desc";

            $listeId = ListeIdTouite::listeTouite($query, [$_GET["user"]]);

            $retour .= ListeRenderer::render($listeId, TouiteRenderer::COURT); //on fait le rendu html de la liste de touite correspondant au ids données


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


        //requete sql qui vas selectionner les idTouite par ordre decroissant sur la date
        $query = "SELECT idTouite FROM `TOUITE` order by date desc";

        $resultat = ListeIdTouite::listeTouite($query, []); //sous traite la requete a une autre classe

        $retour .= ListeRenderer::render($resultat, TouiteRenderer::COURT); //on fait le rendu html de la liste de touite correspondant au ids données


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

        $resultat = ListeIdTouite::listeTouite($query, [$tag]); //sous traite la requete a une autre classe

        $retour .= ListeRenderer::render($resultat, TouiteRenderer::COURT); //on fait le rendu html de la liste de touite correspondant au ids données


        return ($retour);
    }


}