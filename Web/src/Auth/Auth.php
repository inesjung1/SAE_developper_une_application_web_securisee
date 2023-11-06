<?php

namespace Iutncy\Sae\Auth;

class Auth
{

    public static function authentificate(string $email,string $mdp) : boolean
        {
            Iutncy\Sae\Db\ConnectionFactory::makeConnection();
            $bdd = \Iutncy\Sae\Db\ConnectionFactory::makeConnection();
            if (strlen($mdp) < 8) {
                echo "Le mot de passe doit contenir au moins 8 caractères";
            }

        }    
    

}