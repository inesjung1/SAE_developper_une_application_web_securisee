<?php
declare(strict_types=1);
namespace Iutncy\Sae\Action;
class DefaultAction extends Action {
    public function __construct() {}
    public function execute(): string {
        $html = <<<HTML
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