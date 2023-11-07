<?php

namespace touiteur\Dispatch;

use touiteur\Action\ActionDefault;
use touiteur\Action\ActionSignUp;

class Dispatcher
{

    private string $action;

    public function __construct(){
        if(!isset($_GET['action']))
            $_GET['action'] = 'default';
        $this->action = $_GET['action'];

    }


    public function run():void{
        switch($this->action){
            case 'signup':{
                $signup = new ActionSignUp();
                self::renderPage($signup->execute());
                break;
        }
            default:{
                $action=new ActionDefault();
                self::renderPage($action->execute());
                break;
            }
        }
    }


    /*
     * renderPage render html page
     */
    private function renderPage(string $html):void{
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

<link rel="stylesheet" type="text/css" href="../../css/index.css">
</html>
END;

    }
}