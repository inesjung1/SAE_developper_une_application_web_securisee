<?php

namespace Iutncy\Sae\Action;

use Iutncy\Sae\Db\ConnectionFactory;


class MesStatistiques extends Action
{
    public function __construct()
    {
    }

    public function execute(): string
    {
        $html = '';
        $idU = $_COOKIE['user'];
        $love = $this->recupererLike();
        $dislove = $this->recupererDislike();
        $html .= <<<HTML
                <nav>
                    <button class="navi" onclick="window.location.href='index.php?action=DefaultAction'">Touiter</button>
                    <button class="navi" onclick="window.location.href='index.php?action=UtilisateurAction&user=$idU'">Mon Profil</button>
                </nav>
                <p>Nombre de Love Moyen : $love</p>
                <p>Nombre de Dislove Moyen : $dislove</p>
        HTML;
        return $html;
    }

    public function recupererLike(): string
    {
        //récuper le nombre total de like des touites d'un utilisateur
        $db = ConnectionFactory::makeConnection();
        $id = $_COOKIE['user'];
        $sql = "SELECT CEIL(AVG(LOVE)) FROM Touite WHERE UtilisateurID = $id GROUP BY UtilisateurID;";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $like = $stmt->fetch();
        $nbLike = $like['CEIL(AVG(LOVE))'];
        //
        return $nbLike;
    }

    public function recupererDislike(): string
    {
        //récuper le nombre total de like des touites d'un utilisateur
        $db = ConnectionFactory::makeConnection();
        $id = $_COOKIE['user'];
        $sql = "SELECT CEIL(AVG(DISLOVE)) FROM Touite WHERE UtilisateurID = $id GROUP BY UtilisateurID;";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $dislove = $stmt->fetch();
        $nbdislove = $dislove['CEIL(AVG(DISLOVE))'];
        //
        return $nbdislove;
    }

}