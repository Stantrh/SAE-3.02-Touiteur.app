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

        //il faut etre connecté pour pouvoir suivre un utilisateur
        if (isset($_SESSION["user"])){
            if (isset($_GET["id-user-suivre"])) {
                try {
                    $user=unserialize($_SESSION["user"]);
                    $idUser=$user->__get("id");
                    SuivreUser::suivreUser((int)($_GET["id-user-suivre"]), $idUser);
                    $retour = "Vous suivez maintenant une nouvelle personne !";

                }catch(InvalidPropertyNameException $e){
                    $retour = $e->getMessage();
                }catch(InvalidActionException $e1){
                    $retour = "Vous ne pouvez pas vous suivre vous même ! NARCISSIQUE !";
                }catch (\Exception $e2) {
                    $retour = "Vous ne pouvez pas suivre cette personne";
                }
            }
        }else{
            $retour = "Vous n'êtes pas connecté, connectez vous pour pouvoir suivre cette personne";
        }


        return ($retour);
    }
}