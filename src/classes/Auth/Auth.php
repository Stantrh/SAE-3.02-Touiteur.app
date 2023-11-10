<?php
namespace touiteur\Auth;


use touiteur\Database\ConnectionFactory;

use touiteur\Database\User;
use touiteur\Exception\AuthException;

/**
 * Classe regroupant l'ensemble des méthodes liées à l'authentification
 * L'authentification utilise la table UTILISATEUR (qui stocke l'id de l'utilisateur et d'autres infos)
 * et son mdp encodé avec bcrypt (méthode password_hash)
 */
class Auth{


    /**
     * Méthode qui reçoit l'email et mdp en clair d'un utilisateur
     * et contrôle la validité des données
     * @param string $nomUser
     * @param string $mdpClair
     * @return void
     * @throws AuthException
     *  Utilisateur test :
     *  nomUser = HelloWorld
     *  nom = Test
     *  prenom = Numero1
     *  email = test@mail.com
     *  mot de passe = CaFait10Carac*
     *  confirmer mot de passe = CaFait10Carac*
     */
    public static function authenticate(string $nomUser, string $mdpClair){
        // On va déjà vérifier si l'utilisateur est dans la base de données via son email
        $requete = "SELECT COUNT(nomUser) FROM UTILISATEUR where nomUser = ?";

        $count = ConnectionFactory::$db->prepare($requete);
        $count->bindParam(1, $nomUser);

        $count->execute();

        $res = $count->fetchColumn();
        if($res === 1){
            // Alors l'utilisateur a un compte
            // Maintenant on vérifie que le mot de passe associé à ce nom d'utilisateur est le même que dans la bdd
            // On prépare une autre requête
            $requeteMdp = "SELECT mdp from UTILISATEUR where nomUser = ?";
            $result = ConnectionFactory::$db->prepare($requeteMdp);
            $result->bindParam(1, $nomUser);
            $result->execute();


            $mdpHashe = $result->fetchColumn();
            if(!password_verify($mdpClair, $mdpHashe)){
                throw new AuthException('Le mot de passe est invalide');
            }
            // On va ajouter l'utilisateur à la session et donc utiliser la fonction loadProfile
            self::loadProfile($nomUser);
        }else{
            throw new AuthException('<h4>Bonjour, vous n\' avez pas de compte dans la base de données.</h4>');
        }
    }

    /**
     * Permet d'enregistrer un utilisateur en Session
     * @param string $nomUser
     * @return void
     */
    public static function loadProfile(string $nomUser) : void{
        $requete = "select * from UTILISATEUR where nomUser = ? ";
        $u = ConnectionFactory::$db->prepare($requete);
        $u->bindParam(1, $nomUser);
        $u->execute();
        $res = $u->fetch(\PDO::FETCH_ASSOC);

        $profile = new User($res['nom'], $res['prenom'], $res['email'], $res['nomUser'], $res['role'], $res['idUser']);

        $_SESSION['user'] = serialize($profile);

    }


    /**
     * @throws AuthException
     */
    public static function checkAccountOwner(int $id) : void{
        //regarde si il y a un utilisateur en session pour ne pas faire d'erreur d'accès de tableau inexistant
        if(!isset($_SESSION['user'])){
            throw new AuthException("Pas d'utilisateur connéctée");
        }
        $user = unserialize($_SESSION['user']);
        $role = $user->__get('role');
        if(!($role === 100)){ // admin a un role 100
            // Si l'utilisateur n'est pas admin, on vérifie si l'id de l'utilisateur est bien celui de celui qui est en session
            if(!(($user->__get('id')) === $id)) {
                throw new AuthException("Vous n'avez pas les droits nécessaires pour effectuer cette action");
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
        $digit = preg_match("#[\d]#", $pass); // au moins un digit
        $special = preg_match("#[\W]#", $pass); // au moins un car. spécial
        $lower = preg_match("#[a-z]#", $pass); // au moins une minuscule
        $upper = preg_match("#[A-Z]#", $pass); // au moins une majuscule
        if (!$length || !$digit || !$special || !$lower || !$upper)return false;
        return true;
    }

    /**
     * Méthode qui permet à un utilisateur de s'enregistrer dans la base de données
     * Utilisateur test :
     * nomUser = HelloWorld
     * nom = Test
     * prenom = Numero1
     * email = test@mail.com
     * mot de passe = CaFait10Carac*
     * confirmer mot de passe = CaFait10Carac*
     * @throws AuthException
     */
    public static function register(string $username, string $nom, string $prenom, string $email, string $mdpClair, string $mdpClair2 ="erreur"): void {
        // On vérifie d'abord si l'utilisateur existe dans la BDD
        $requete = "SELECT COUNT(nomUser) FROM UTILISATEUR WHERE nomUser = ?";
        $result = ConnectionFactory::$db->prepare($requete);
        $result->bindParam(1, $username); // l'username est unique, tout comme l'email donc au choix
        $result->execute();
        $res = $result->fetchColumn();

        // Si l'utilisateur n'existe pas déjà dans la base
        if ($res == 0) {
            // On vérifie d'abord si les deux mots de passe sont les mêmes
            if(strcmp($mdpClair, $mdpClair2) === 0){
                // On vérifie si le mot de passe est conforme
                if (self::checkPasswordStrength($mdpClair, 10)) {
                    // Ensuite on le hash s'il est conforme
                    $mdpHashe = password_hash($mdpClair, PASSWORD_DEFAULT);

                    // On prépare l'insertion
                    $register = <<<SQL
INSERT INTO UTILISATEUR (nom, prenom, email, nomUser, mdp, role) VALUES (?, ?, ?, ?, ?, ?)
SQL;
                    $insert = ConnectionFactory::$db->prepare($register);
                    $insert->bindParam(1, $nom);
                    $insert->bindParam(2, $prenom);
                    $insert->bindParam(3, $email);
                    $insert->bindParam(4, $username);
                    $insert->bindParam(5, $mdpHashe);
                    $role = "1";
                    $insert->bindParam(6, $role);
                    $insert->execute();

                    // Puis on enregistre l'utilisateur dans la session
                    self::loadProfile($username);
                } else {
                    throw new AuthException("<h3 id = \"error\">Le mot de passe entré ne respecte pas les normes de sécurité</h3>");
                }
            } else{
                throw new AuthException("<h3 id = \"error\">Les deux mots de passe ne correspondent pas</h3>");
            }
        } else {
            throw new AuthException("<h3 id = \"error\">Un compte avec cet email est déjà enregistré</h3>");
        }
    }
}
