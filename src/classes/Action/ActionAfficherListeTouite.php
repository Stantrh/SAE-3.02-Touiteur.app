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

                break;
            case self::UTILISATEUR:

                break;

            default:
                $retour .= $this->default();
                break;
        }
        return ($retour);
    }

    /**
     * @return string code html d'une liste de touite du plus récent au plus vieux
     */
    private function default(): string
    {

        $retour = "";

        //sql

        $query = "SELECT idTouite FROM `TOUITE` order by date desc";

        $resultat = ListeIdTouite::listeTouite($query, []); //sous traite la requete a une autre classe

        $retour .= ListeRenderer::render($resultat, TouiteRenderer::COURT); //on fait le rendu html de la liste de touite correspondant au ids données


        return ($retour);

    }

}