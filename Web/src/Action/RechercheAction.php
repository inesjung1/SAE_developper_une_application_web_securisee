<?php
declare(strict_types=1);
namespace Iutncy\Sae\Action;
use Iutncy\Sae\Touites\ListTouite;
use Iutncy\Sae\User\User;
class RechercheAction extends Action {
    public function __construct() {}
    public function execute(): string {
        $recherche = $_GET['recherche'];
        $listRecherche = new ListTouite();
        //$listRecherche->loadFromDb($recherche);
        $html = <<<HTML
            <button class="navi" onclick="window.location.href='index.php?action=DefaultAction'">Touiter</button>
            <button class="btnConnection" onclick="window.location.href='index.php?action=connection'">Connection</button>
            <button class="btnInscription" onclick="window.location.href='index.php?action=inscription'">Inscription</button>
            <form action="index.php?action=recherche" method="get">
                <input type="hidden" value="recherche" name="action">
                <input type="text" id="recherche" name="recherche" placeholder="Recherche">
                <input type="submit" value="Recherche">
            </form>
        HTML;
        return $html;
    }
}