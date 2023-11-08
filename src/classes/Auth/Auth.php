<?php
namespace touiteur\Auth;


use touiteur\Database\ConnectionFactory;
use touiteur\Exception\AuthException;

/**
 * Classe regroupant l'ensemble des méthodes liées à l'authentification
 * L'authentification utilise la table User (qui stocke l'id de l'utilisateur = email)
 * et son mdp encodé avec bcrypt (méthode password_hash)
 */
class Auth{


    /**
     * Méthode qui reçoit l'email et mdp en clair d'un utilisateur
     * et contrôle la validité des données
     * @param string $email
     * @param string $mdpClair
     * @return void
     * @throws AuthException
     */
    public static function authenticate(string $email, string $mdpClair){
        // On va déjà vérifier si l'utilisateur est dans la base de données via son email
        $requete = "    SELECT COUNT(email) FROM user where email = ?";
        ConnectionFactory::setConfig(__DIR__.'/../../../config/.ini');
        ConnectionFactory::makeConnection();

        $count = \iutnc\deefy\db\ConnectionFactory::$db->prepare($requete);
        $count->bindParam(1, $email);

        $count->execute();

        $res = $count->fetchColumn();

        if($res === 1){
            // Alors l'utilisateur a un compte
            // Maintenant on vérifie que le mot de passe associé à cet email est le même que dans la bdd
            // On prépare une autre requête
            $requeteMdp = "SELECT passwd from user where email = ?";
            $result = ConnectionFactory::$db->prepare($requeteMdp);
            $result->bindParam(1, $email);
            $result->execute();

            $mdpHashe = $result->fetchColumn();
            if(!password_verify($mdpClair, $mdpHashe)){
                throw new AuthException('Le mot de passe est invalide');
            }
            // On va ajouter l'utilisateur à la session et donc utiliser la fonction loadProfile
            self::loadProfile($email);
        }else{
            throw new AuthException('Bonjour, vous n\' avez pas de compte dans la base de données.');
        }
    }

    /**
     * @param string $email
     * @return void
     */
    public static function loadProfile(string $email) : void{
        $requete = "select * from user where email = ? ";
        ConnectionFactory::setConfig(__DIR__.'/../../../config/.ini');
        ConnectionFactory::makeConnection();
        $u = \iutnc\deefy\db\ConnectionFactory::$db->prepare($requete);
        $u->bindParam(1, $email);
        $u->execute();
        $res = $u->fetch(\PDO::FETCH_ASSOC);

        $profile = new User($res['email'], $res['passwd'], $res['role']);

        $_SESSION['user'] = serialize($profile);

    }

    /**
     * @throws AuthException
     */
    public static function checkPlaylistOwner(int $idPlaylist) : void{
        $user = unserialize($_SESSION['user']);
        $role = $user->__get('role');
        if(!($role === 100)){ // admin a un role 100
            // Si l'utilisateur n'est pas admin, on vérifie au moins que la playlist lui appartient
            $requete = <<<SQL
select id_pl from user2playlist inner JOIN user on user2playlist.id_user = user.id where user.email = ?
SQL;
            ConnectionFactory::setConfig(__DIR__.'/../../../config/.ini');
            ConnectionFactory::makeConnection();
            $idsPl = ConnectionFactory::$db->prepare($requete);
            $email = $user->__get('email');
            $idsPl->bindParam(1, $email);
            $idsPl->execute();

            // on parcourt les colonnes (donc les track associées à la playlist
            $trouve = false;
            while($row = $idsPl->fetch(\PDO::FETCH_ASSOC)){
                if($row['id_pl'] === $idPlaylist){
                    $trouve = true;
                    break;
                }
            }
            if(!$trouve){
                throw new AuthException("Vous n'avez pas les droits nécessaires pour accéder à cette playlist");
            }
        }
    }





    /**
     * Permet de retourner si un mot de passe est conforme à la norme de sécurité
     * @param string $pass
     * @param int $minimumLength
     * @return bool
     */
    public static function checkPasswordStrength(string $pass,
                                                 int $minimumLength): bool {
        $length = (strlen($pass) > $minimumLength); // longueur minimale
//        $digit = preg_match("#[\d]#", $pass); // au moins un digit
//        $special = preg_match("#[\W]#", $pass); // au moins un car. spécial
//        $lower = preg_match("#[a-z]#", $pass); // au moins une minuscule
//        $upper = preg_match("#[A-Z]#", $pass); // au moins une majuscule
        if (!$length)return false; // || !$digit || !$special || !$lower || !$upper
        return true;
    }

    /**
     * Méthode qui permet à un utilisateur de s'enregistrer dans la base de données
     *
     * @throws AuthException
     */
    public static function register(string $email, string $mdpClair): void {
        // On vérifie d'abord si l'utilisateur existe dans la BDD
        $requete = "SELECT COUNT(email) FROM USER WHERE email = ?";
        ConnectionFactory::setConfig(__DIR__.'/../../../config/.ini');
        ConnectionFactory::makeConnection();
        $result = \iutnc\deefy\db\ConnectionFactory::$db->prepare($requete);
        $result->bindParam(1, $email);
        $result->execute();
        $res = $result->fetchColumn();

        // Si l'utilisateur n'existe pas déjà dans la base
        if ($res == 0) {
            // On vérifie si le mot de passe est conforme
            if (self::checkPasswordStrength($mdpClair, 10)) {
                $mdpHashe = password_hash($mdpClair, PASSWORD_DEFAULT);

                // On prépare l'insertion
                $register = "INSERT INTO USER (email, passwd) VALUES (?, ?)";
                $insert = ConnectionFactory::$db->prepare($register);
                $insert->bindParam(1, $email);
                $insert->bindParam(2, $mdpHashe);
                $insert->execute();
            } else {
                throw new AuthException("<h3 id = \"error\">Le mot de passe entré n'est pas assez long</h3>");
            }
        } else {
            throw new AuthException("<h3 id = \"error\">Un compte avec cet email est déjà enregistré</h3>");
        }
    }
}
