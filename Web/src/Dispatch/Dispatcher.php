<?php
namespace Iutncy\Sae\Dispatch;
use Iutncy\Sae\Action\ConnectionAction;
use \Iutncy\Sae\Action\DefaultAction;
use \Iutncy\Sae\Action\InscriptionAction;
use \Iutncy\Sae\Action\ShowTouite;
use \Iutncy\Sae\Action\UtilisateurAction;
use \Iutncy\Sae\Action\RechercheAction;
use \Iutncy\Sae\Action\LoveAction;
use \Iutncy\Sae\Action\DisloveAction;
use \Iutncy\Sae\Action\DeconnexionAction;
use \Iutncy\Sae\Action\AbonnementsAction;
use \Iutncy\Sae\Action\Sabonner;
use \Iutncy\Sae\Action\SeDesabonner;
use \Iutncy\Sae\Action\AbonnementsTag;
use \Iutncy\Sae\Action\SabonnerTag;
use \Iutncy\Sae\Action\SeDesabonnerTag;
use \Iutncy\Sae\Action\MesAbonnesAction;
use \Iutncy\Sae\Action\DeleteTouite;
use \Iutncy\Sae\Action\AfficherTouiteAction;
use \Iutncy\Sae\Action\MesStatistiques;
class Dispatcher
{
    private $action;

    public function __construct()
    {
        // Récupère la valeur du paramètre "action" du query-string
        $this->action = isset($_GET['action']) ? $_GET['action'] : 'defaultaction';
    }

    public function run():void
    {
        // Utilise un switch pour déterminer quelle classe Action instancier
        switch ($this->action) {
            case 'AfficherTouiteAction':
                $action = new AfficherTouiteAction();
                break;
            case 'recherche':
                $action = new RechercheAction();
                break;
            case 'UtilisateurAction':
                $action = new UtilisateurAction();
                break;
            case 'connection':
                $action = new ConnectionAction();
                break;
            case 'inscription':
                $action = new InscriptionAction();
                break;
            case 'affichertouite':
                $action = new ShowTouite();
                break;
            case 'loveaction':
                $action = new LoveAction();
                break;
                case 'disloveaction':
                    $action = new DisloveAction();
                    break;
            case 'deconnexionaction':
                $action = new DeconnexionAction();
                break;
            case 'AbonnementsAction':
                $action = new AbonnementsAction();
                break;
            case 'Sabonner':
                $action = new Sabonner();
                break;
            case 'SeDesabonner':
                $action = new SeDesabonner();
                break;
            case 'AbonnementsTag':
                $action = new AbonnementsTag();
                break;
            case 'SabonnerTag':
                $action = new SabonnerTag();
                break;
            case 'SeDesabonnerTag':
                $action = new SeDesabonnerTag();
                break;
            case 'MesAbonnesAction':
                $action = new MesAbonnesAction();
                break;
            case 'DeleteTouite':
                $action = new DeleteTouite();
                break;
            case 'MesStatistiques':
                $action = new MesStatistiques();
                break;
            default:
                $action = new DefaultAction();
                break;
        }
        $this->renderPage($action->execute());
    }

    private function renderPage(string $html): void
    {
        $base = <<<HTML
          <!DOCTYPE html>
          <html lang="fr">
          <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Touiter</title>
             <link rel="stylesheet" href="./src/style.css">
        </head>
        <body>
             <header>
         <div class="header-image">
            <img src="src/LogoA.png" alt="Touiter Logo">
        </div>
        <nav>
            <ul>
                <li><a href="index.php?action=connection">Connexion</a></li>
                <li><a href="index.php?action=inscription">Inscription</a></li>
                <li><a href="index.php?action=affichertouite">Touites</a></li>
                <li><a href="index.php?action=AbonnementsAction">Abonnements</a></li>
                <li><a href="index.php?action=MesAbonnesAction">Mes Abonnés</a></li>
                <li><a href="index.php?action=deconnexionaction">Deconnexion</a></li>
            </ul>
        </nav>
        </header>
        <main>
            $html
        </main>
        </body>
        </html>
    HTML;
        echo $base;
    }
}

