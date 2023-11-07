<?php

namespace touiteur\Renderer;

class ListeRenderer
{
    public static function render(array $listeId,string $option):string{
        $retour="<div class='listeTouite'>";

        foreach($listeId as $touite){

        $retour.=TouiteRenderer::render($touite,$option);
        }

        return($retour."</div>");
    }

}