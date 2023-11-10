<?php

session_start();
require_once "../vendor/autoload.php";

use touiteur\Database\ConnectionFactory;
use touiteur\Database\LikesDislikes;

ConnectionFactory::setConfig(__DIR__.'/../config/config.ini'); //fichier de config pour mysql
ConnectionFactory::makeConnection(); //debut de la connection pour mysql à ne faire qu'une seule fois dans le projet


if(isset($_SESSION)){
    if (isset($_GET['idTouite']) && isset($_GET['appreciation'])) {
        $postId = $_GET['idTouite'];
        $appreciation = $_GET['appreciation'];
        $user = unserialize($_SESSION['user']);
        $idUser = $user->__get('id');

        switch ($appreciation) {
            case 'like':
                LikesDislikes::toggleLike($postId, $idUser);
                break;
            case 'dislike':
                LikesDislikes::toggleDislike($postId, $idUser);
                break;
            default:
                break;
        }

        echo LikesDislikes::getPostScore($postId);
    } else {
        echo "Problèmes de paramètres dans le querystring"; // Pour debug sinon impossible
    }
}else{
//    throw new \touiteur\Exception\AuthException("Vous devez être connecté");
    echo "vous devez etre connecté";
}


