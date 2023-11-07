<?php

namespace Iutncy\Sae\Auth;
use Iutncy\Sae\User\User;
use Iutncy\Sae\Db\ConnectionFactory;
use Iutncy\Sae\Exception\AuthException;
class Auth
{

    public static function authentificate(string $email, string $mdp) : bool
        {
            ConnectionFactory::makeConnection();
            $bdd = ConnectionFactory::$db;
            $requete = $bdd->prepare('SELECT * FROM Utilisateur WHERE AdresseEmail = :email');
            $requete->bindValue(':email', $email);

            $resultat = $requete->execute();

            if($resultat){
                while($row = $requete->fetch()){
                    $passwordHash = $row['MDP'];
                    $verifier = password_verify($mdp, $passwordHash);
                    if($verifier){
                        $user = new User($row['AdresseEmail'], $row['PSEUDO'], $row['MDP']);
                        $_SESSION['user'] = serialize($user);
                        return true;

                    }
                    else{
                        echo "Le mot de passe est incorrect";
                        throw new AuthException("Le mot de passe est incorrect");
                    }
                }
            }
            return false;
        }





    public static function register(string $pseudo, string $email,string $mdp)
        {
            
            
            ConnectionFactory::makeConnection();
            $bdd = ConnectionFactory::$db;
            //vérification de la longueur du mot de passe, pas de nettoyage car on affiche pas le mot de passe.
            if (strlen($mdp) < 8) {
                echo "Le mot de passe doit contenir au moins 8 caractères";
                throw new AuthException("Le mot de passe doit contenir au moins 8 caractères");
            }
            
            
            
            
            //vérification de la validité de l'email et nettoyage de l'email
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo "L'email n'est pas valide";
                throw new AuthException("L'email n'est pas valide");
            }
            $email = filter_var($email, FILTER_SANITIZE_EMAIL);






            //vérification de l'unicité de l'email
            $requete = $bdd->prepare('SELECT * FROM Utilisateur WHERE AdresseEmail = :email');
            $requete->bindValue(':email', $email);
            $resultat = $requete->execute();
            if ($resultat){
                while($row = $requete->fetch()){
                    if ($row['email'] == $email){
                        echo "L'email est déjà utilisé";
                        throw new AuthException("L'email est déjà utilisé");

                    }
                }
            }
            //hashage du mot de passe

            $passwordHash = password_hash($mdp, PASSWORD_DEFAULT);
            $requete = $bdd->prepare('INSERT INTO Utilisateur (AdresseEmail, mdp, pseudo) VALUES (:email, :mdp, :pseudo)');
            $requete->bindValue(':email', $email);
            $requete->bindValue(':mdp', $passwordHash);
            $requete->bindValue(':pseudo', $pseudo);
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