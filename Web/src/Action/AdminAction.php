<?php

namespace Iutncy\Sae\Action;

class AdminAction extends Action {
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
        return $html;
    }
}