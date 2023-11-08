<?php

namespace touiteur\Action;

use touiteur\Database\ListeIdTouite;
use touiteur\Renderer\ListeRenderer;
use touiteur\Renderer\TouiteRenderer;

class ActionAfficherListeTouitePaginer extends Action
{
private const NBTOUITE=3;
    /**
     * @return string liste de touite paginer en fonction de l'option page dans le $_GET
     */
    public function execute(): string
    {
        $retour="";
        $page=0;
        $pageSuivante=1;
        $pagePrecedente=0;

        $query="select idTouite from `TOUITE` order by date desc limit ? offset ?";
        if(isset($_GET["page"]) && $_GET["page"]>=0){
            $page=$_GET["page"];
            $pageSuivante=$page+1;
            $pagePrecedente=$page-1;
        }

        $resultat=ListeIdTouite::listeTouite($query,[self::NBTOUITE,$page*self::NBTOUITE]);
        $retour=ListeRenderer::render($resultat,TouiteRenderer::COURT);

        $retour.=<<<END
<div class="bouton-pagination">

<a class="boutton-paginer" href="?action=afficher-liste-touite-paginer&page=$pagePrecedente">
Page Precedente
</a>
<a class="boutton-paginer" href="?action=afficher-liste-touite-paginer&page=$pageSuivante">
Page Suivante
</a>
</div>


END;


        return($retour);
    }
}