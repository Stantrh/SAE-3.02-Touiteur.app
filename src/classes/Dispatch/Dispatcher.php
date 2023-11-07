<?php

namespace touiteur\Dispatch;

use touiteur\Action\ActionAfficherListeTouite;
use touiteur\Action\ActionDefault;

class Dispatcher
{

    private string $action;

    public function __construct(){
        if(!isset($_GET['action']))
            $_GET['action'] = 'default';
        $this->action = $_GET['action'];

    }
    /*
    $db = ConnectionFactory::$db;   // a faire a chaque fois qu'on veut se connecter a la base
    $query = "SELECT passwd FROM `User` WHERE email= ?"; //requette sql
    $st = $db->prepare($query);
    $st->execute(["user1@mail.com"]); //execute la requette et remplace le ? par le parametre dans le tableau
    $row = $st->fetch();    //fetch pour l'affichage de la première ligne
    echo($row[0]); //affichage de la première ligne
    */
    /*
     * si on veux parcourir entièrement le resultat
    while($row=$st->fetch(PDO::FETCH_ASSOC)) {

        foreach ($row as $v) {
            echo  $v .":" ;
        }
        echo("\n");

    }
    */

    public function run():void{
        switch($this->action){
            case "afficher-liste-touite":
                $action=new ActionAfficherListeTouite(ActionAfficherListeTouite::DEFAULT);
                $this->renderPage($action->execute());
                break;
            default:
                $action=new ActionDefault();
                $this->renderPage($action->execute());
                break;
        }
    }


    /*
     * renderPage render html page
     */
    public function renderPage(string $html):void{
        echo <<< END
<!DOCTYPE html>
<html lang="fr">
	<head>
		<title>Projet Web</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" href="../../css/index.css" >
	</head>

END;

echo $html;

echo <<< END


</html>
END;

    }
}