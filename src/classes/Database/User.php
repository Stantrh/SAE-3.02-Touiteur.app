<?php

namespace touiteur\Database;

use touiteur\Exception\InvalidPropertyNameException;

/**
 * Classe représentant un utilisateur, qui sera évidemment stocké en session
 */
class User
{
    /**
     * Informations de l'utilisateur
     */
    private string $nom, $prenom, $email, $nomUser;
    /**
     * @var int role de l'utilisateur, ainsi que son identifiant
     */
    private int $role, $id;


    /**
     * Construit un utilisateur à partir de toutes ses informations dans la base de données, pour pouvoir le sérialiser et le mettre en session
     * @param string $nom
     * @param string $prenom
     * @param string $nomUser
     * @param int $role
     * @param int $id
     */
    public function __construct(string $nom, string $prenom, string $nomUser, string $email, int $role, int $id){
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
        $this->nomUser = $nomUser;
        $this->role = $role;
        $this->id = $id;
    }


    /**
     * @throws InvalidPropertyNameException
     */
    public function __get(string $attr): mixed
    {
        if (property_exists($this, $attr)) {
            return $this->$attr;
        }
        throw new InvalidPropertyNameException(get_called_class() . " invalid property : <$attr>");

    }
}