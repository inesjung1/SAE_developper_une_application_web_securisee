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
                }
            }
            return false;
        }





    public static function register(string $pseudo, string $email,string $mdp,string $nom,string $prenom) : bool
        {
            
            $isok = true;
            ConnectionFactory::makeConnection();
            $bdd = ConnectionFactory::$db;
            //vérification de la longueur du mot de passe, pas de nettoyage car on affiche pas le mot de passe.
            
                if (strlen($mdp) < 8) {
                    echo "Le mot de passe est trop court !";
                    $isok = $isok && false ;
    
                }
            
            
            
            
            
            
            
            
            //vérification de la validité de l'email et nettoyage de l'email
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo "L'email n'est pas valide";
                $isok = $isok && false;
                throw new AuthException("L'email n'est pas valide");
            }







            //vérification de l'unicité de l'email
            $requete = $bdd->prepare('SELECT * FROM Utilisateur WHERE AdresseEmail = :email');
            $requete->bindValue(':email', $email);
            $resultat = $requete->execute();
            if ($resultat){
                while($row = $requete->fetch()){
                    if ($row['AdresseEmail'] == $email){
                        echo "L'email est déjà utilisé";
                        $isok = $isok && false;

                    }
                }
            }
            //hashage du mot de passe

            //vérification de l'unicité du pseudo
            $requete = $bdd->prepare('SELECT * FROM Utilisateur WHERE PSEUDO = :pseudo');
            $requete->bindValue(':pseudo', $pseudo);
            $resultat = $requete->execute();
            if ($resultat){
                while($row = $requete->fetch()){
                    if ($row['PSEUDO'] == $pseudo){
                        echo "Le pseudo est déjà utilisé";
                        $isok = $isok && false;

                    }
                }
            }


            
            if($isok){
                $passwordHash = password_hash($mdp, PASSWORD_DEFAULT);
                $requete = $bdd->prepare('INSERT INTO Utilisateur (Nom, Prenom, AdresseEmail, mdp, pseudo) VALUES (:nom, :prenom, :email, :mdp, :pseudo)');
                $requete->bindValue(':nom', $nom);
                $requete->bindValue(':prenom', $prenom);
                $requete->bindValue(':email', $email);
                $requete->bindValue(':mdp', $passwordHash);
                $requete->bindValue(':pseudo', $pseudo);
                $resultat = $requete->execute();
                //vérification de l'ajout de l'utilisateur
                if ($resultat === false){
                    $isok = $isok && false;
                    }
                  
            }
            return $isok;   
            
    }
}