<?php

namespace touiteur\Dispatch;

use touiteur\Action\ActionAfficherListeTouite;
use touiteur\Action\ActionAfficherListeTouiteUser;
use touiteur\Action\ActionAfficherTouiteDetail;
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
            case 'signup':
                $signup = new ActionSignUp();
                self::renderPage($signup->execute());
                break;
            case "afficher-liste-touite":
                $action=new ActionAfficherListeTouite(ActionAfficherListeTouite::DEFAULT);
                $this->renderPage($action->execute());
                break;
            case "afficher-touite-detail":
                $action=new ActionAfficherTouiteDetail();
                $this->renderPage($action->execute());
                break;
            case "afficher-touite-user":
                $action=new ActionAfficherListeTouiteUser();
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
    private function renderPage(string $html):void{

        $css = __DIR__.'/../../css/index.css';
        echo <<<END
                <!DOCTYPE html>
                <html lang="fr">
                    <head>
                        <title>Projet Web</title>
                        <style>
                            .error{
                                color:red;
                            }
                            
                            body {
                                font-family: Arial, sans-serif;
                                background-color: #f0f0f0;
                                margin: 0;
                                padding: 0;
                            }
                            
                            header {
                                background-color: #459496;
                                color: #fff;
                                text-align: center;
                                padding: 10px;
                            }
                            
                            header h1{
                                font-size: 3em;
                                margin-top: 5px;
                                margin-bottom: 0px;
                            }
                            
                            main {
                                max-width: 800px;
                                margin: 20px auto;
                                padding: 20px;
                                background-color: #fff;
                                box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
                            }
                            
                            h1, h4 {
                                text-align: center;
                            }
                            
                            p {
                                text-align: center;
                            }
                            
                            .touiteCourt {
                                padding: 10px;
                                margin: 10px;
                                border: 1px solid green;
                                background-color: red;
                                color: #fff;
                                border-radius: 5px;
                            }
                            
                            div:hover {
                                background-color: #fff;
                                color: red;
                            }

                            .corpsTouite {
                                background-color: black;
                                color: #fff;
                            }
                            
                            button {
                                background-color: #f0f0f0;
                                color: #000000;
                                font-size: 20px;
                                border: none;
                                transition: all 0.3s ease;
                                border-radius: 5px;
                                padding: 10px 20px;
                                display: block;
                                margin: 0 auto;
                                text-decoration: none;
                            }
                            
                            button:hover {
                                background-color: #b29a00;
                            }
                            
                            button:active {
                                transform: scale(0.9);
                            }
                            
                            header p {
                                margin: 10px;
                            }
                            
                            .menu-box {
                                display: flex;
                                justify-content: center;
                                border: 1px none #ccc;
                                border-radius: 5px;
                                padding: 10px;
                                margin: 20px 0;
                            }
                            
                            /* Styles pour les éléments du menu */
                            .menu-box p {
                                margin-top: 0;
                                margin-bottom: 0;
                                text-align: center;
                            }
                            
                            .menu-box a {
                                text-decoration: none;
                                color: #ffffff;
                                padding: 10px 20px;
                                border: 3px solid #ffffff;
                                border-radius: 5px;
                                background-color: #459496;
                                transition: all 0.3s ease;
                            }
                            
                            .menu-box a:hover {
                                background-color: #ffffff;
                                border: 3px solid #ffffff;
                                color: #000;
                                
                                
                                /*border: 3px solid #ffffff;
                                background-color: #459496;
                                color: #ffffff;*/
                            }
                        </style>
                        <meta charset="utf-8">
                        <meta name="viewport" content="width=device-width, initial-scale =1.0">
                        <link rel="stylesheet" href="$css">
                    </head>
                    <body>
                        <header>
                            <h1>Touiteur</h1>
                            <div class="menu-box">
                                <p><a href="?">Accueil</a></p>
                                <p><a href="?action=signup">Inscription</a></p>
                                <p><a href="?action=signin">Se connecter</a></p>
                            </div>
                        </header>
                        <main>
                            $html
                        </main>
                    </body>
                        
                    <!--<link rel="stylesheet" type="text/css" href="../../css/index.css">-->
                </html>
        END;
    }
}