<?php

namespace Iutncy\Sae\Auth;

class Auth
{

    public static function authentificate(string $email, string $mdp) : boolean
        {
            \Iutncy\Sae\Db\ConnectionFactory::makeConnection();
            $bdd = \Iutncy\Sae\Db\ConnectionFactory::$db;
            $requete = $bdd->prepare('SELECT * FROM user WHERE email = :email');
            $requete->bindValue(':email', $email);

            $resultat = $requete->execute();

            if($resultat){
                while($row = $requete->fetch()){
                    $passwordHash = $row['password'];
                    $verifier = password_verify($mdp, $passwordHash);
                    if($verifier){
                        $user = new \Iutncy\Sae\User($row['id'], $row['email']);
                        $_SESSION['user'] = serialize($user);
                        return true;

                    }
                    else{
                        echo "Le mot de passe est incorrect";
                        throw new \Iutncy\Sae\Exception\AuthException("Le mot de passe est incorrect");
                    }
                }
            }
            
        
        }





    public static function register(string $email,string $mdp) 
        {
            
            
            Iutncy\Sae\Db\ConnectionFactory::makeConnection();
            $bdd = \Iutncy\Sae\Db\ConnectionFactory::$db;
            //vérification de la longueur du mot de passe, pas de nettoyage car on affiche pas le mot de passe.
            if (strlen($mdp) < 8) {
                echo "Le mot de passe doit contenir au moins 8 caractères";
                throw new \Iutncy\Sae\Exception\AuthException("Le mot de passe doit contenir au moins 8 caractères");
            }
            
            
            
            
            //vérification de la validité de l'email et nettoyage de l'email
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo "L'email n'est pas valide";
                throw new \Iutncy\Sae\Exception\AuthException("L'email n'est pas valide");
            }
            $email = filter_var($email, FILTER_SANITIZE_EMAIL);






            //vérification de l'unicité de l'email
            $requete = $bdd->prepare('SELECT * FROM user WHERE email = :email');
            $requete->bindValue(':email', $email);
            $resultat = $requete->execute();
            if ($resultat){
                while($row = $requete->fetch()){
                    if ($row['email'] == $email){
                        echo "L'email est déjà utilisé";
                        throw new \Iutncy\Sae\Exception\AuthException("L'email est déjà utilisé");

                    }
                }
            }
            //hashage du mot de passe

            $passwordHash = password_hash($mdp, PASSWORD_DEFAULT);
            $requete = $bdd->prepare('INSERT INTO user (email, password) VALUES (:email, :password)');
            $requete->bindValue(':email', $email);
            $requete->bindValue(':password', $passwordHash);
            $resultat = $requete->execute();
            //vérification de l'ajout de l'utilisateur
            if ($resultat){
                echo "L'utilisateur a bien été ajouté";
            }
            else{
                echo "L'utilisateur n'a pas pu être ajouté";
            }    
    }
}