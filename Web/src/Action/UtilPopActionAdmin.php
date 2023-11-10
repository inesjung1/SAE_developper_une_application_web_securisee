<?php

namespace Iutncy\Sae\Action;

use Iutncy\Sae\Db\ConnectionFactory;
use Iutncy\Sae\Render\UtilRenderer;

class UtilPopActionAdmin extends Action
{
    public function __construct()
    {
    }

    public function execute(): string
    {
        if (!isset($_COOKIE['user'])) {
            setcookie('user', "0", time() + 3600, '/');
        }
        $idU = $_COOKIE['user'];
        $html = <<<HTML
                <nav>
                <button class="navi" onclick="window.location.href='index.php?action=DefaultAction'">Accueil</button>
               HTML;
        if ($_COOKIE['user'] != 0) {
            $html .= <<<HTML
            <button class="navi" onclick="window.location.href='index.php?action=TendancesAdmin'">Tendances</button>
            <button class="navi" onclick="window.location.href='index.php?action=UtilPopActionAdmin'">Influenceurs</button>
            HTML;
        }else{
            $html .= <<<HTML
            <button class="navi" onclick="window.location.href='index.php?action=TendancesAdmin'">Tendances</button>
            <button class="navi" onclick="window.location.href='index.php?action=UtilPopActionAdmin'">Influenceurs</button>
            HTML;
        }
        $html .= <<<HTML
            </nav>
        HTML;
        $db = ConnectionFactory::makeConnection();
        $sql = "SELECT COUNT(*) AS NombreOccurrences, SuiviUtilisateurID 
        FROM abonnement
        GROUP BY SuiviUtilisateurID
        ORDER BY NombreOccurrences DESC;";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $res = $stmt->fetchAll();
        $add = $res;
        $star = [];
        for($i = 0; $i < 3; $i++){
            $star[$i] = $add[$i]['SuiviUtilisateurID'];
        }
        foreach ($star as $s){
            $affiche = New UtilRenderer($s);
            $af = $affiche->render(1, "UtilisateurAction");
            $html .= <<<HTML
                <div class="touite-header">
                    <div class="touite-pseudo">
                    $af
                    </div>
            HTML;
        }
        return $html;
    }
}