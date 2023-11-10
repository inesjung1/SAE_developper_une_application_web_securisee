<?php
declare(strict_types=1);
namespace Iutncy\Sae\Action;
use Iutncy\Sae\Auth\Auth;
use Iutncy\Sae\Db\ConnectionFactory;
class ConnectionAction extends Action {
public function __construct() {}
    public function execute(): string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $html = <<<HTML
        <button class="navi" onclick="window.location.href='index.php?action=DefaultAction'">Touiter</button>
        <div class="center-container">
            <form action="index.php?action=connection" method="post">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <input type="submit" value="Connexion" class="custom-button">
            </form>
        </div>
    HTML;
        } else {
            $email = $_POST['email'];
            $password = $_POST['password'];
            //recuperation de l'id
            $db = ConnectionFactory::makeConnection();
            $sql = "SELECT UtilisateurID FROM Utilisateur WHERE AdresseEmail = '$email'";
            $stmt = $db->prepare($sql);
            $stmt->execute();
            $idU = $stmt->fetch()['UtilisateurID'];
            //transformer $idU en string
            $idU = (string) $idU;
            $Auth = new Auth();
            if ($Auth->authentificate($email, $password)) {
                setcookie('user', $idU, time() + 3600, '/');
                $html = <<<HTML
                <button class="navi" onclick="window.location.href='index.php?action=DefaultAction'">Touiter</button>
                <div class="center-container">
                    <p class="success-message">Vous êtes bien connecté</p>
                </div>
            HTML;
            } else {
                $html = <<<HTML
                    <button class="navi" onclick="window.location.href='index.php?action=DefaultAction'">Touiter</button>
                    <p class="success-message">Erreur de connection</p>
                HTML;
            }
        }
        return $html;
    }
}