<?php

namespace touiteur\Renderer;

class ListeRenderer
{
    /**
     * @param array $listeId liste des ids des touites a afficher
     * @param string $option option d'affichage des touites, const de TouiteRenderer
     * @return string   liste des touites en html
     */
    public static function render(array $listeId, string $option): string
    {
        $retour = "<div class='listeTouite'>";

        if (count($listeId) === 0) {
            $retour.="Pas de touite";
        } else {
            foreach ($listeId as $touite) {
                //on fait le rendu des touites un par un et on les ajoutes au retour

                $retour .= TouiteRenderer::render($touite, $option);
            }
        }
        return ($retour . "</div>");
    }

}