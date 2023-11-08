<?php

namespace touiteur\Action;

use Exception;
use touiteur\Database\ConnectionFactory;

class ActionPublierTouite extends Action
{

    public function execute():  string
    {
        // String à construire et à renvoyer
        $contenuHTML = "";

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $contenuHTML .= <<<FINI
                                <form method="post" action="?action=publier-touite" enctype="multipart/form-data">
                                <fieldset>
                                    <legend> Ajouter un nouveau Touite </legend>
                                    <input type="text" name="contenu" placeholder="Contenu de votre Touite" required>
                                    <input type="file" name="image" accept="image/png, image/jpeg, image/jpg" placeholder="Une image pour illustrer votre Touite ?">
                                    <button type="submit" name="ajouter_playlist" value="ajouter_p1">Ajouter</button>
                                </fieldset>
                            FINI;

        } else { // POST

            if (isset($_SESSION["user"])) {
                $user=unserialize($_SESSION["user"]);
                $idUser=$user->__get("id");
                // on nettoye le contenu du Touite
                $contenuNettoye = filter_var($_POST['contenu'], FILTER_SANITIZE_STRING);


                // on regarde si l'utilisateur veut ajouter une image
                $targetFile = null;
                //le if qui suit sert a insere l'image si elle existe
                if (isset($_FILES["image"])) {
                    $ext=explode(".",$_FILES['image']['name']);
                    $extAdmissible=["jpg","png","jpeg"];

                    //si t'arrive la c'est
                    if (in_array($ext[1],$extAdmissible)) {

                        //ça c'est les trucs de nathan qui marche pas
                        $image = $_FILES["image"]["tmp_name"];
                        $targetDir = "../../../images/";
                        $targetFile = $targetDir .  $image;
                        echo $targetFile;

                        //requette pour inserer le chemin de fichier de l'image
                        $requeteInsertionImage=<<<END
                        INSERT INTO `IMAGE` (cheminFichier,description) 
                        VALUES (?,'');
                        END;
                        $st = ConnectionFactory::$db->prepare($requeteInsertionImage);
                        $st->execute([$image]); //$image c'est le chemin de fichier de l'image sur le serveur


                        //pour bouger le fichier, ça marche pas
                        move_uploaded_file($image, $targetFile);


                        //IL FAUT QU'A LA FIN DE CE IF LE CHEMIN DU FICHIER SUR LE SERVEUR SOIT DANS LA VARIABLE $targetFile

                    }
                }

                // On crée un nouveau Touite
                $requeteInsertion = <<<FIN
                                    INSERT INTO TOUITE (idUser, date, texteTouite, idImage, score)
                                    VALUES (?, STR_TO_DATE(NOW(), '%Y-%m-%d %H:%i:%s'), ? , ?, 0)
                                FIN;

                //connexion à la base de données
                $st = ConnectionFactory::$db->prepare($requeteInsertion);

                // on exécute l'insertion
                try {
                    $st->execute([$idUser,$contenuNettoye, $targetFile]);
                    $contenuHTML .= "Touite publié !";

                    //on extrait des tags éventuels dans le contenu du touite pour les ajouter dans la base de données

                    //on range chaque mot du contenu dans une case d'un tableau
                    $tab = explode(" ", $contenuNettoye);

                    // on créé un tableau de résultats qui va contenir les tags éventuels
                    $resTags = array();

                    // on parcours tout le tableau afin de vérifier si il en contient pas de tag, spécifiés à l'aide de #
                    foreach ($tab as $value){
                        //si la case du tableau contient un tag
                        if(str_contains($value, "#")){
                            //on met le tag dans le tableau de résultat
                            $resTags[] = $value;
                        }
                    }
                    
                    //on insert tous les tags dans la base de données
                    foreach($resTags as $tagAAjouter){
                        //on préprare la requete d'insertion des tags dans la base de données
                        $requeteInsererTag = <<<FIN
                                        INSERT INTO TAG (libelle, description)
                                        VALUES (?,?)
                                    FIN;

                        //connexion à la base de données
                        $st = ConnectionFactory::$db->prepare($requeteInsererTag);

                        // on complète la requete SQL
                        $st->bindParam(1, $tagAAjouter);
                        $st->bindParam(2, $tagAAjouter);

                        // puis on exécute la requête
                        $st->execute();
                    }

                } catch (Exception $e) {
                    $contenuHTML .= $e->getMessage();
                }
            }
        }
        return $contenuHTML;
    }

}