<?php

namespace touiteur\Dispatch;

use touiteur\Action\ActionAfficherListeTouite;
use touiteur\Action\ActionAfficherListeTouitePaginer;
use touiteur\Action\ActionAfficherListeTouiteUser;
use touiteur\Action\ActionAfficherStatistiqueCompte;
use touiteur\Action\ActionAfficherTouiteDetail;
use touiteur\Action\ActionDefault;
use touiteur\Action\ActionPublierTouite;
use touiteur\Action\ActionSignOut;
use touiteur\Action\ActionSignUp;
use touiteur\Action\ActionSignIn;
use touiteur\Action\ActionSuivreTag;
use touiteur\Action\ActionSuivreUtilisateur;
use touiteur\Action\ActionSupprimerTouite;

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
                $action = new ActionSignUp();
                self::renderPage($action->execute());
                break;
            case 'signin':
                $action= new ActionSignIn();
                self::renderPage($action->execute());
                break;
            case 'signout':
                $action = new ActionSignOut();
                self::renderPage($action->execute());
                break;
            case "afficher-liste-touite":
                $action=new ActionAfficherListeTouite(ActionAfficherListeTouite::DEFAULT);
                self::renderPage($action->execute());
                break;
            case "afficher-liste-touite-en-entier":
                $action=new ActionAfficherListeTouite(ActionAfficherListeTouite::TOUSLESTOUITES);
                self::renderPage($action->execute());
                break;
            case "afficher-touite-detail":
                $action=new ActionAfficherTouiteDetail();
                self::renderPage($action->execute());
                break;
            case "afficher-touite-user":
                $action=new ActionAfficherListeTouite(ActionAfficherListeTouite::UTILISATEUR);
                self::renderPage($action->execute());
                break;
            case "afficher-liste-tag":
                $action=new ActionAfficherListeTouite(ActionAfficherListeTouite::TAG);
                self::renderPage($action->execute());
                break;
            case "publier-touite":
                $action=new ActionPublierTouite();
                $this->renderPage($action->execute());
                break;
            case "supprimer-touite":
                $action=new ActionSupprimerTouite();
                $this->renderPage($action->execute());
                break;
            case "suivre-user":
                $action=new ActionSuivreUtilisateur();
                $this->renderPage($action->execute());
                break;
            case "suivre-tag":
                $action=new ActionSuivreTag();
                $this->renderPage($action->execute());
                break;
            case "statistique-compte":
                $action=new ActionAfficherStatistiqueCompte();
                $this->renderPage($action->execute());
                break;
            default:
                $action=new ActionDefault();
                self::renderPage($action->execute());
                break;
        }

    }


    /*
     * renderPage render html page
     */
    private function renderPage(string $html):void{


        $res = <<<END
                <!DOCTYPE html>
                <html lang="fr">
                    <head>
                        <title>Projet Web</title>
                        <style>
                            
                            
                            
                        </style>
                        <meta charset="utf-8">
                        <meta name="viewport" content="width=device-width, initial-scale =1.0">
                        <link href="./css/index.css" rel="stylesheet" type="text/css"/>
                    </head>
                    <body>
                        <header>
                            <h1><a href="?action=default" id="titre">Touiteur</a></h1>
                            <div class="menu-box">
                                <p><a href="?">Accueil</a></p>
                                <p><a href="?action=afficher-liste-touite-en-entier">Découvrir</a></p>
END;
    // On vérifie si l'utilisateur est connecté pour savoir quoi afficher
        if(isset($_SESSION['user'])){
            $res .= <<<END
                                <p><a href="?action=publier-touite">Touiter</a></p>
                                <p><a href="?action=suivre-tag">Suivre un tag</a></p>
                                <p><a href="?action=statistique-compte">Statistiques</a></p>
                                <p><a href="?action=signout">Se deconnecter</a></p>
END;
        }else{
            $res .= <<<END
                                <p><a href="?action=signin">Se connecter</a></p>
                                <p><a href="?action=signup">Inscription</a></p>
END;
        }

        $res .= <<<END
                                <div class="dropdown">
                                    <button class="dropbtn">Menu</button>
                                    <div class="dropdown-content">
                                        <p><a href="?action=signup">Inscription</a></p>
                                        <p><a href="?action=publier-touite">Touiter</a></p>
                                        <p><a href="?action=signin">Se connecter</a></p>
                                    </div>
                                </div>
                            </div>
                            
                        </header>
                        <div class="main">
                            <div>
                                $html
                            </div>
                        </div>
                        <a href="?action=publier-touite">
                            <button class="touiter">
                                <img src="classes/Dispatch/touiter-stylo2.png" alt="icone de stylo">
                                Touiter
                            </button>
                        </a>
                        
                        <!--
                        <div class="touiter">
                            <a href="?action=publier-touite">
                                <p>Touiter</p>
                            </a>
                        </div>
                        -->
                    </body>
                        
                </html>
END;

        echo $res;
    }
}