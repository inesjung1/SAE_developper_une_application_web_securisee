<?php
declare(strict_types=1);
namespace Iutncy\Sae\Action;
class DefaultAction extends Action {
    public function __construct() {}
    public function execute(): string {
        $html = <<<HTML
            <button class="btnConnection" onclick="window.location.href='index.php?action=connection'">Connection</button>
            <button class="btnInscription" onclick="window.location.href='index.php?action=inscription'">Inscription</button>
        HTML;
        return $html;
    }
}