<?php
session_start(); // On aura besoin d'une session

require_once __DIR__."/../vendor/autoload.php";
use \touiteur\Dispatch\Dispatcher;
use \touiteur\Database\ConnectionFactory;



ConnectionFactory::setConfig(__DIR__.'/../config/config.ini'); //fichier de config pour mysql
ConnectionFactory::makeConnection(); //debut de la connection pour mysql à ne faire qu'une seule fois dans le projet

$dispatch=new Dispatcher();
$dispatch->run();   // run() va s'occuper d'afficher la page html
