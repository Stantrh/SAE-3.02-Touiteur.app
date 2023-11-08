<?php

namespace touiteur\Action;

use touiteur\Renderer\TouiteRenderer;

class ActionAfficherTouiteDetail extends Action
{
    /**
     * @return string touite en version long
     */
    public function execute(): string
    {
        if(isset($_GET["id-touite"])){
            $retour=TouiteRenderer::render($_GET["id-touite"],TouiteRenderer::LONG);
        }else{
            //erreur error
            $retour = "Pas de touite avec cet id";
        }
        return($retour);

    }
}