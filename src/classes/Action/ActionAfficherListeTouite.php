<?php

namespace touiteur\Action;

use PDO;
use touiteur\Database\ConnectionFactory;
use touiteur\Renderer\ListeRenderer;
use touiteur\Renderer\TouiteRenderer;

class ActionAfficherListeTouite extends Action
{

    public const TAG = "tag";
    public const UTILISATEUR = "utilisateur";
    public const DEFAULT = "default";

    private string $option;

    function __construct(string $option)
    {
        parent::__construct();
        $this->option = $option;

    }

    function execute(): string
    {
        $retour = "";
        switch ($this->option) {
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

    private function default(): string
    {
        $retour = "";

        $db = ConnectionFactory::$db;
        $query = "SELECT idTouite FROM `TOUITE` order by date desc";
        $st = $db->prepare($query);
        $st->execute();
        $row = $st->fetch();

        if ($row) {
            $listeId = array();
            $listeId[] =$row[0];
            while ($row = $st->fetch(PDO::FETCH_ASSOC)) {

                foreach ($row as $v) {
                    $listeId[] =($v);

                }

            }

            $retour.=ListeRenderer::render($listeId,TouiteRenderer::COURT);


        } else {
            $retour = "Pas de touit";
        }


        return ($retour);

    }

}