<?php


require_once "../vendor/autoload.php";
use \touiteur\Dispatch\Dispatcher;

use touiteur\Connection\ConnectionFactory;

ConnectionFactory::setConfig('./config.ini'); //fichier de config pour mysql
ConnectionFactory::makeConnection(); //debut de la connection pour mysql a ne faire qu'une seule fois dans le projet


$dispatch=new Dispatcher();
$dispatch->run();   // run() vas s'occuper d'afficher la page html

