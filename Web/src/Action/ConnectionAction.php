<?php
declare(strict_types=1);
namespace Iutncy\Sae\Action;
use Iutncy\Sae\Auth\Auth;
class ConnectionAction extends Action {
public function __construct() {}
    public function execute(): string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $html = <<<HTML
            <nav>
                <button onclick="window.location.href='index.php?action=DefaultAction'">Touiter</button>
                <form action="index.php?action=recherche" method="get">
                    <input type="hidden" value="recherche" name="action">
                    <input class="entreeTexte" type="text" name="recherche" placeholder="Recherche">
                    <input class="entreeButton" type="submit" value="Recherche">
                </form>
                <button onclick="window.location.href='index.php?action=connection'">Connexion</button>
                <button onclick="window.location.href='index.php?action=inscription'">Inscription</button>
                <button onclick="window.location.href='index.php?action=utilisateuraction'">Touitez</button>
            </nav>
            
            <form action="index.php?action=connection" method="post">
                <label for="email">Email</label>
                <input class="entreeTexte" type="email" id="email" name="email" required>
                <label for="password">Mot de passe</label>
                <input class="entreeTexte" type="password" id="password" name="password" required>
                <input class="entreeButton" type="submit" value="Connection">
            </form>
            HTML;
        } else {
            $email = $_POST['email'];
            $password = $_POST['password'];
            $Auth = new Auth();
            if ($Auth->authentificate($email, $password)) {
                $html = <<<HTML
                    <p>Vous êtes bien connecté</p>
                HTML;
            } else {
                $html = <<<HTML
                    <p>Erreur de connection</p>
                HTML;
            }
        }
        return $html;
    }
}