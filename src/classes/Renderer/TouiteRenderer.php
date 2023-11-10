<?php

namespace touiteur\Renderer;

use PDO;
use touiteur\Auth\Auth;
use touiteur\Database\ConnectionFactory;


class TouiteRenderer
{
    const LONGUEURTOUITECOURT = 50;
    const LONG = "long";
    const COURT = "court";

    /**
     * @param int $id id du touite a faire le rendu
     * @param string $option option de rendue du touite, const de classe TouiteRenderer
     * @return string  touite en html
     */

    public static function render(int $id, string $option): string
    {
        $retour = "";
        switch ($option) {
            case self::LONG:
                $retour = self::renderLong($id);
                break;
            case self::COURT:
                $retour = self::renderCourt($id);
                break;
            default:
                $retour = self::renderCourt($id);
                break;


        }
        return ($retour);
    }


    /**
     * rend le html du touite en mode long
     * @param int $id id du touite a rendre
     * @return string
     */
    private static function renderLong(int $id): string
    {
        $retour = "";


        //sql
        $db = ConnectionFactory::$db;
        $query = "SELECT * FROM `TOUITE` WHERE idTouite=?";
        $st = $db->prepare($query);
        $st->execute([$id]);
        $row = $st->fetch();

        if ($row) {     //verification de l'existance du touite

            $htmlImage = "";
            if ($row["idImage"] != NULL) { //si il y a une image on fait les requetes pour obtenir l'image
                //sql
                $query = "SELECT * FROM `IMAGE` WHERE idImage=?";
                $st = $db->prepare($query);
                $st->execute([$row["idImage"]]);
                $row1 = $st->fetch();

                $image = $row1["cheminFichier"]; //on stock les infos


                $descriptionimage = $row1["description"];
                $htmlImage = <<<HTML

<img src="$image" class="img" alt="$descriptionimage">
HTML;
            }
            $profile = ProfileRenderer::render($row["idUser"]);

            $boutonSupprimer = "";

            try {
                Auth::checkAccountOwner($row["idUser"]);
                $boutonSupprimer = <<<END
                                    <a class="bouton-supprimer" href="?action=supprimer-touite&id-touite-supprimer=$id">
                                    Supprimer le touite
                                    </a>
                                    END;


            } catch (\Exception $e) {

            }

            $userASuivre = $row["idUser"];
            $score = $row['score'];
            $boutonSuivreUser = <<<END
                                <a class="bouton-suivre" href="?action=suivre-user&id-user-suivre=$userASuivre">
                                Suivre
                                </a>
                                END;
            //on transforme les tags dans le texte en lien vers les touite de tags
            $texteTouite=TouiteRenderer::detecterTransformerTag($row["texteTouite"]);


            // on construit le html du touite avec les differents éléments qu'on a récupéré
            $retour = <<<END
<div class='touite' data-idTouite="$id">\n
    <div class="profil">
        $profile
        $boutonSuivreUser
    </div>


    <p class ='corpsTouite-long' > $texteTouite </p>

     $htmlImage
     
<div class='score'>
    <span id="score">Score : <span id="valeurScore_$id">$score</span></span>
    <button class="voteButton likeButton" data-idTouite="$id">Like</button>
    <button class="voteButton dislikeButton" data-idTouite="$id">Dislike</button>
</div>

<script>


document.querySelectorAll('.voteButton').forEach(function(button) {
    button.addEventListener('click', function(event) {
        var idTouite = this.getAttribute('data-idTouite'); // on récupère l'id du poste à partir du bouton
        var boutonDejaLike = this.classList.contains('likeButton');
        toggleLikeDislike.bind(this)(idTouite, boutonDejaLike);
    });
});

function toggleLikeDislike(idTouite, boutonDejaLike) {
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var score = document.getElementById('valeurScore_' + idTouite);
            score.innerHTML = xhr.responseText;
        }
    };

    var action = boutonDejaLike ? 'like' : 'dislike';

    // Utilise la méthode bind pour lier le contexte de "this"
    var dejaLike = boutonDejaLike && this.classList.contains('liked');
    var dejaDislike = !boutonDejaLike && this.classList.contains('disliked');

    if (dejaLike || dejaDislike) {
        action = 'remove' + action.charAt(0).toUpperCase() + action.slice(1);
    }

    var url = "http://localhost:63342/SAE-3.02-Touiteur.app/src/GestionLikesDislikes.php?idTouite=" + idTouite + "&appreciation=" + action;
    console.log(url);
    xhr.open('GET', url, true);
    xhr.send();
}
</script>

$boutonSupprimer 
</div>\n
END;
        } else {
            $retour = "pas de touite avec cette id:" . $id;
        }
        return ($retour);

    }

    /**
     * @param int $id id du touite a rendre
     * @return string
     */
    private static function renderCourt($id): string
    {
        $retour = "";
        //sql
        $db = ConnectionFactory::$db;
        $query = "SELECT * FROM `TOUITE` WHERE idTouite=?";
        $st = $db->prepare($query);
        $st->execute([$id]);
        $row = $st->fetch();

        if ($row) {

            $texte = substr($row["texteTouite"], 0, self::LONGUEURTOUITECOURT) . "..."; //pour l'affichage court on coupe a un certain nombre de charactère

            //action qui doit s'executer quand on clique sur le texte du touite, ici on affiche le touite en detail
            $actionCliqueTouite = "?action=afficher-touite-detail&id-touite=$id";
            $profile = ProfileRenderer::render($row["idUser"]);

            // on construit le touite court
            $retour .= <<<END
            <div class='touiteCourt'>
                    $profile
                    <a href="$actionCliqueTouite" class='touite-clickable'>
                        <p class ='corpsTouite'> $texte </p>
                    </a>
            </div>\n
END;
        } else {
            $retour = "pas de touite avec cette id:" . $id;
        }
        return ($retour);
    }

    /**
     * @param string $texte texte d'entré contenant des tags
     * @return string meme chose que le texte d'entrée les tags sont devenus des liens vers la page affichant tout les touites de ces tags
     */
    private static function detecterTransformerTag(string $texte): string
    {
        //on coupe le texte au espace pour trouver les #
        $strExplode = explode(" ", $texte);

        $strFinal = "";

        //on check pour tout les "mots" si c'est un hashtag
        foreach ($strExplode as $item) {

            //si c'est un hashtag on met un lien, si c'est pas un hashtag on ne fait rien
            if (!empty($item) && $item[0] == "#") {
                $tag=substr($item,1);
                $item = <<<END
<a class="tag-clickable" href="?action=afficher-liste-tag&tag=$tag"> $item </a>
END;
            }

            //on ajoute le mot pour constituer petit a petit le touite
            $strFinal .= $item . " ";
        }
        return ($strFinal);
    }

}