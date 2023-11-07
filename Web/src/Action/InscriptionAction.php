<?php
declare(strict_types=1);
namespace Iutncy\Sae\Action;
use Iutncy\Sae\User\User;
use Iutncy\Sae\Auth\Auth;
class InscriptionAction extends Action {
    public function __construct() {}
    public function execute(): string {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $html = <<<HTML
                <form action="index.php?action=inscription" method="post">
                    <label for="pseudo">Pseudo</label>
                    <input type="text" id="pseudo" name="pseudo" required>
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" required>
                    <input type="submit" value="Inscription">
                </form>
            HTML;
        } else {
            $pseudo = $_POST['pseudo'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $Auth = new Auth();
            $Auth->register($pseudo, $email, $password);
            $html = <<<HTML
                <p>Vous êtes bien inscrit</p>
                <button class="btnConnection" onclick="window.location.href='index.php?action=connection'">Connection</button>
            HTML;
        }
        return $html;
    }
}