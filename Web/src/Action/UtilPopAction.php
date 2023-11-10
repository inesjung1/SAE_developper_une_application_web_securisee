<?php

namespace Iutncy\Sae\Action;

use Iutncy\Sae\Db\ConnectionFactory;

class UtilPopAction extends Action
{
    public function __construct()
    {
    }

    public function execute(): string
    {
        $db = ConnectionFactory::makeConnection();
        $sql = "SELECT u.adresseemail 
                FROM utilisateur u
                JOIN (SELECT SuiviUtilisateurID 
                      FROM abonnement 
                      GROUP BY SuiviUtilisateurID 
                      ORDER BY COUNT(*) DESC
                      LIMIT 3) 
                a ON u.utilisateurid = a.SuiviUtilisateurID;";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $res = $stmt->fetchAll();
        $star = [];
        for($i=0;$i<3;$i++){
            $star[$i] = $res[$i]['AdresseEmail'];
        }
    }
}