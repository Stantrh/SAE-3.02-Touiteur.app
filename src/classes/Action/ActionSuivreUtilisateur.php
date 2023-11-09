<?php

namespace touiteur\Action;

use touiteur\Exception\InvalidActionException;
use touiteur\Exception\InvalidPropertyNameException;
use touiteur\Database\SuivreUser;

class ActionSuivreUtilisateur extends Action
{

    public function execute(): string
    {
        $retour = "Aucune action ";
        try {
            //il faut etre connecté pour pouvoir suivre un utilisateur
            if (isset($_SESSION["user"])){
                //on vérifie que l'id de l'utilisateur est correctement reçu
                if (isset($_GET["id-user-suivre"])) {
                    $user=unserialize($_SESSION["user"]);
                    $idUser=$user->__get("id");
                    //on vérifie que l'utilisateur ne veut pas se suivre lui même
                    if($_GET["id-user-suivre"] != $idUser){
                        SuivreUser::suivreUser((int)($_GET["id-user-suivre"]), $idUser);
                        $retour = "Vous suivez maintenant une nouvelle personne !";
                    }else{
                        $retour = "Vous ne pouvez pas vous suivre vous même ! NARCISSIQUE !";
                    }
                }else{
                    $retour = "Problème encore inconnu...";
                }
            }else{
                $retour = "Vous n'êtes pas connecté, connectez vous pour pouvoir suivre cette personne";
            }
        }catch(InvalidPropertyNameException $e1){
            $retour = $e1->getMessage();
        }catch (\Exception $e2) {
            $retour = "Vous ne pouvez pas suivre cette personne";
        }
            return ($retour);
    }
}