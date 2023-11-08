<?php

namespace touiteur\Action;

use touiteur\Database\ListeIdTouite;
use touiteur\Renderer\ListeRenderer;
use touiteur\Renderer\TouiteRenderer;

class ActionAfficherListeTouitePaginer extends Action
{
    private const NBTOUITEMAX = 3;  //nombre de touite max par page paginé

    /**
     * @return string liste de touite paginer en fonction de l'option page dans le $_GET
     */
    public function execute(): string
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



        $bouttonSuivant=<<<END
        href="?action=afficher-liste-touite-paginer&page=$pageSuivante"
        END;

        //enleve le bouton suivant si il n'y a plus de touite a afficher après, a noter que le bouton suivant sera toujours affiché sur la dernière page si le nombre de touite total est multiple de NBTOUITEMAX
        if(count($resultat)<self::NBTOUITEMAX){
            $bouttonSuivant="";
        }



        $bouttonPrecedent = <<<END
        href="?action=afficher-liste-touite-paginer&page=$pagePrecedente"
        END;

        //enleve le bouton suivant si il n'y a pas de page precedente
        if ($page === $pagePrecedente || $pagePrecedente<0) {
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
}