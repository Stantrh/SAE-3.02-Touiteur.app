<?php

namespace touiteur\Action;

use Exception;
use touiteur\Database\ConnectionFactory;

class ActionPublierTouite extends Action
{

    public function execute(): string
    {
        // String à construire et à renvoyer
        $contenuHTML = "";

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $contenuHTML .= <<<FINI
                                <form method="post" action="?action=publier-touite" enctype="multipart/form-data">
                                <fieldset>
                                    <legend> Publier un nouveau Touite </legend>
                                    <input type="text" name="contenu" placeholder="Contenu de votre Touite" required>
                                    <input type="file" name="image" accept="image/png, image/jpeg, image/jpg" placeholder="Une image pour illustrer votre Touite ?">
                                    <button class="bouton-publier-touite" type="submit" name="ajouter_playlist">Publier</button>
                                </fieldset>
                            FINI;

        } else { // POST

            if (isset($_SESSION["user"])) {
                $user = unserialize($_SESSION["user"]);
                $idUser = $user->__get("id");
                // on nettoye le contenu du Touite
                $contenuNettoye = filter_var($_POST['contenu'], FILTER_SANITIZE_STRING);


                // on regarde si l'utilisateur veut ajouter une image
                $targetFile = null;

                //le if qui suit sert a insere l'image si elle existe
                if (isset($_FILES["image"]) && strlen($_FILES['image']['name']) > 3) {
                    $ext = explode(".", $_FILES['image']['name']);
                    $extAdmissible = ["jpg", "png", "jpeg"];

                    // L'extension du fichier est validée
                    if (in_array($ext[1], $extAdmissible)) {
                        require __DIR__ . '/../../upload.php';


                        $image = '../images/' . $_FILES['image']['name'];
                        //Requete pour inserer le chemin de fichier de l'image
                        $requeteInsertionImage = <<<END
                        INSERT INTO `IMAGE` (cheminFichier,description) 
                        VALUES (?,'Image uploadée par un utilisateur');
                        END;
                        $st = ConnectionFactory::$db->prepare($requeteInsertionImage);
                        $st->execute([$image]); //$image c'est le chemin de fichier de l'image sur le serveur

                        // Puis dans targetFile, on met l'id de l'image qui vient d(être insérée pour le retrouver après
                        $targetFile = ConnectionFactory::$db->lastInsertId();
                    }

                    //on enleve l'image du tableau files pour ne pas avoir de problèmes lors de la prochaine insertion d'image
                    unset($_FILES["image"]);
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
                    $st->execute([$idUser, $contenuNettoye, $targetFile]);
                    $contenuHTML .= "Touite publié !";


                    //on extrait des tags éventuels dans le contenu du touite pour les ajouter dans la base de données
                    //on range chaque mot du contenu dans une case d'un tableau
                    $tab = explode(" ", $contenuNettoye);

                    // on créé un tableau de résultats qui va contenir les tags éventuels
                    $resTags = array();

                    // on parcours tout le tableau afin de vérifier si il en contient pas de tag, spécifiés à l'aide de #
                    foreach ($tab as $value) {
                        //si la case du tableau contient un tag
                        if (str_contains($value, "#")) {
                            //on vérifie que le # se trouve en première position
                            if (strpos($value, "#") == 0) {
                                //on met le tag dans le tableau de résultat
                                $resTags[] = $value;
                            }
                        }
                    }

                    //on insert tous les tags dans la base de données
                    foreach ($resTags as $tagAAjouter) {
                        //on préprare la requete d'insertion des tags dans la base de données
                        $requeteInsererTag = <<<FIN
                                                    INSERT INTO TAG (libelle, description)
                                                    VALUES (?,?)
                                                FIN;

                        //connexion à la base de données
                        $st = ConnectionFactory::$db->prepare($requeteInsererTag);

                        //la description du tag c'est juste le tag sans le #, donc on enleve le premier charactère du tag
                        $descriptionTag = substr($tagAAjouter, 1);

                        // on complète la requete SQL
                        $st->bindParam(1, $tagAAjouter);
                        $st->bindParam(2, $descriptionTag);


                        //la requete renvoie une erreur si le touite existe déjà
                        try {
                            // puis on exécute la requête
                            $st->execute();
                        }catch (Exception $exception){

                        }
                        // on récupère l'id du dernier touite
                        //requete
                        $requeteRecupererIdTouite = <<<FIN
                                                    SELECT MAX(idTouite) FROM TOUITE
                                                FIN;

                        //connexion à la base de données
                        $st = ConnectionFactory::$db->prepare($requeteRecupererIdTouite);
                        $st->execute();

                        // on récupère le résultat de la requete SQL
                        $row = $st->fetch();

                        // on récupère l'id du dernier touite ajouté
                        $idTouite = $row[0];

                        // on récupère l'id du dernier tag
                        //requete
                        $requeteRecupererIdTag = <<<FIN
                                                    SELECT idTag FROM TAG where libelle=?
                                                FIN;

                        //connexion à la base de données
                        $st = ConnectionFactory::$db->prepare($requeteRecupererIdTag);
                        $st->execute([$tagAAjouter]);

                        // on récupère le résultat de la requete SQL
                        $row = $st->fetch();

                        // on récupère l'id du dernier tag ajouté
                        $idTag = $row[0];

                        // on insert le touite et le tag dans la table tag2touite
                        //on préprare la requete d'insertion des tags dans la base de données
                        $requeteInsererTag2Touite = <<<FIN
                                                        INSERT INTO TAG2TOUITE (idTouite, idTag)
                                                        VALUES (?,?)
                                                    FIN;

                        //connexion à la base de données
                        $st = ConnectionFactory::$db->prepare($requeteInsererTag2Touite);

                        // on complète la requete SQL
                        $st->bindParam(1, $idTouite);
                        $st->bindParam(2, $idTag);

                        // on execute la requete
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