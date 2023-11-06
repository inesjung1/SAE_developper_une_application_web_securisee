<?php

namespace Iutncy\Sae\Dispatch;
use \Iutncy\Sae\Action\DefaultAction;

class Dispatcher
{
    private $action;

    public function __construct()
    {
        // Récupère la valeur du paramètre "action" du query-string
        $this->action = isset($_GET['action']) ? $_GET['action'] : 'add-user';
    }

    public function run():void
    {
        // Utilise un switch pour déterminer quelle classe Action instancier
        switch ($this->action) {
            default:
                $action = new DefaultAction();
                break;
        }
        $this->renderPage($action->execute());
    }

    private function renderPage(string $html): void
    {
        echo '<html>';
        echo '<head>';
        echo '<title>Touite</title>';
        echo '</head>';
        echo '<body>';
        echo $html;
        echo '</body>';
        echo '</html>';
    }
}
