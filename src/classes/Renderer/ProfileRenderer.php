<?php

namespace touiteur\Renderer;

use touiteur\Database\ConnectionFactory;

class ProfileRenderer
{

    /**
     * @param int $id id du profile a render
     * @return string
     */
    public static function render(int $id): string
    {
        $retour = "";

        //sql
        $db = ConnectionFactory::$db;
        $query = "SELECT * FROM `UTILISATEUR` WHERE idUser=?";
        $st = $db->prepare($query);
        $st->execute([$id]);
        $row = $st->fetch();
        if ($row) {
            $actionCliqueProfile="?action=afficher-touite-user&user=$id";
            $retour =<<<END
            <a href="$actionCliqueProfile" class="lien-auteur">
                <div class='user'> {$row["nom"]} {$row["prenom"]}</div>
            </a>
END;
        } else {
            $retour = "Pas d'utilisateur correspondant a l'id:" . $id;
        }
        return $retour;
    }
}