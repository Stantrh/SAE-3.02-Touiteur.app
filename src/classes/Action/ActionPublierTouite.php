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
                                    <legend> Ajouter un nouveau Touite </legend>
                                    <input type="text" name="contenu" placeholder="Contenu de votre Touite" required>
                                    <input type="file" name="image" accept="image/png, image/jpeg" placeholder="Une image pour illustrer votre Touite ?">
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
                if (isset($_FILES["image"])) {
                    if ((substr($_FILES['image']['name'], -4) === '.png') || (substr($_FILES['image']['name'], -5) === '.jpeg') || (substr($_FILES['image']['name'], -4) === '.jpg') && ($_FILES["userfile"]["type"] === 'image/png') || ($_FILES["userfile"]["type"] === 'image/jpeg')) {
                        $image = $_FILES["image"]["tmp_name"];
                        // on déplace l'image dans un répertoire défini, ici le dossier images à la racine du projet
                        $targetDir = "../../../images/"; //répertoire de destination pour l'image
                        move_uploaded_file($image, $targetFile);

                        //emplacement de l'image
                        $targetFile = $targetDir . $image;
                    }
                }

                // On crée un nouveau Touite
                $requeteInsertion = <<<FIN
                                    INSERT INTO TOUITE (idUser, date, texteTouite, idImage, score)
                                    VALUES (?, STR_TO_DATE(NOW(), '%Y-%m-%d %H:%i:%s'), ? , ?, 0)
                                FIN;

                //connexion à la base de données
                $st = ConnectionFactory::$db->prepare($requeteInsertion);

                // complétion de la requete
                $st->execute([$idUser,$contenuNettoye, $targetFile]);


                // on exécute l'insertion
                try {
                    $st->execute();
                    $contenuHTML .= "Touite publié !";
                } catch (Exception $e) {
                    $contenuHTML .= $e->getMessage();
                }
            }
        }
        return $contenuHTML;
    }

}