<?php
require '../vendor/autoload.php';

use Upload\Storage\FileSystem;
use Upload\File;
use Upload\Validation\Mimetype;
use Upload\Validation\Size;

$storage = new FileSystem('../images'); // Répertoire où les fichiers seront stockés
$file = new File('image', $storage);

// Les types MIME autorisés
$file->addValidations([
    new Mimetype(['image/jpeg', 'image/png', 'image/jpg']),
    new Size('5M') // Limite la taille du fichier à 5 Mo
]);

// Essayez d'uploader le fichier
try {
    $file->upload();
    echo 'Le fichier a été téléchargé avec succès!';
} catch (Exception $e) {
}