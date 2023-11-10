<?php

namespace touiteur\Action;

use mysql_xdevapi\Exception;
use touiteur\Auth\Auth;
use touiteur\Database\SuivreTag;

class ActionSuivreTag extends Action
{

    public function execute(): string
    {
        $retour = "";

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $retour = <<<END
            <form method="post" action="?action=suivre-tag" enctype="multipart/form-data">
                <fieldset>
                    <legend> Ajouter un nouveau Touite </legend>
                    <input type="text" name="tag" placeholder="Tag a suivre" required>
                    <button type="submit" name="suivreTag" >Suivre Tag</button>
                </fieldset>
END;


        } else {

            if (isset($_POST["tag"]) && isset($_SESSION["user"])) {
                try {
                    $tag = $_POST["tag"];
                    $tag=filter_var($tag, FILTER_SANITIZE_STRING);
                    if (strlen($tag) > 1) {
                        if ($tag[0] == '#') {
                            $tag = explode(' ', $tag)[0];
                        }else{
                            $tag = '#'.explode(' ', $tag)[0];
                        }
                    }else{
                        throw new Exception("Ce tag n'est pas valide");
                    }
                    //on recupere l'utilisateur en session et on le verifie
                    $user = unserialize($_SESSION["user"]);
                    $idUser = $user->__get("id");
                    Auth::checkAccountOwner($idUser);

                    SuivreTag::suivreTag($tag, $idUser);
                    $retour .= "Le tag ".$tag." est suivie";
                } catch (\Exception $e) {
                    $retour .= $e->getMessage();
                }

            }else{
                $retour.="Mauvais tag ou utilisateur pas connecté";
            }
        }
        return ($retour);
    }
}