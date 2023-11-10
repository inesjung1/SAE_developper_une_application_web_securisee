<?php

namespace Iutncy\Sae\Action;

use Iutncy\Sae\Db\ConnectionFactory;

class MesAbonnesAction extends Action
{
    public function __construct()
    {
    }

    public function execute(): string{
        if (!isset($_COOKIE['user'])) {
            setcookie('user', "0", time() + 3600, '/');
        }
        //afficahge sur une page web des personnes abonnés à notre profil
        $html = '';
        $db = ConnectionFactory::makeConnection();
        $id = $_COOKIE['user'];
        $sql = "SELECT Pseudo, UtilisateurID FROM utilisateur WHERE UtilisateurID IN (SELECT AbonneUtilisateurID FROM abonnement WHERE SuiviUtilisateurID = $id);";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $abonnements = $stmt->fetchAll();
        //On parcourt les éléments de $abonnements
        $html = '';
        $html .= <<<HTML
                <button class="navi" onclick="window.location.href='index.php?action=DefaultAction'">Touiter</button>
                <button class="navi" onclick="window.location.href='index.php?action=UtilisateurAction&user=$id'">Mon Profil</button>
        HTML;
        foreach ($abonnements as $abonnement) {
            $pseudo = $abonnement['Pseudo'];
            $idU = $abonnement['UtilisateurID'];
            $html .= <<<HTML
                <p><a class="user" href="index.php?action=UtilisateurAction&user=$idU">$pseudo</a></p><br>
            HTML;

            //affichage des pseudos des personnes abonnés les uns en dessous des autres

        }
        return $html;
    }

}