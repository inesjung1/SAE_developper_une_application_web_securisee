<?php
namespace Iutncy\Sae\Dispatch;
use Iutncy\Sae\Action\ConnectionAction;
use \Iutncy\Sae\Action\DefaultAction;
use \Iutncy\Sae\Action\InscriptionAction;
use \Iutncy\Sae\Action\ShowTouite;
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
            case 'connection':
                $action = new ConnectionAction();
                break;
            case 'inscription':
                $action = new InscriptionAction();
                break;
            case 'affichertouite':
                $action = new ShowTouite();
                break;
            default:
                $action = new DefaultAction();
                break;
        }
        $this->renderPage($action->execute());
    }

    private function renderPage(string $html): void
    {
        $base = <<< HTML
            <html>
            <head>
            <meta charset="UTF-8">
            <title>Touite</title>
            <link rel="stylesheet" href="./src/style.css">
            </head>
            <body>
            <button onclick="window.location.href='index.php?action=DefaultAction'">Touite</button>
            <br>
            $html
            </body>
            </head>
            </html>
        HTML;

        echo $base;
    }
}
