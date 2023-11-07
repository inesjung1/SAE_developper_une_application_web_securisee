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
                <form action="index.php?action=connection" method="post">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" required>
                    <input type="submit" value="Connection">
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