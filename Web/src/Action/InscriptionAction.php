<?php
declare(strict_types=1);
namespace Iutncy\Sae\Action;
use Iutncy\Sae\User\User;
use Iutncy\Sae\Auth\Auth;
class InscriptionAction extends Action {
    public function __construct() {}
    public function execute(): string {
        if (!isset($_COOKIE['user'])) {
            setcookie('user', "0", time() + 3600, '/');
        }
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $html = <<<HTML
            <nav>
                <button class="navi" onclick="window.location.href='index.php?action=DefaultAction'">Acceuil</button>
            </nav>
            <form action="index.php?action=inscription" method="post">
         <div class="center-container2">
            <div class="form-group2">
                <label for="pseudo">Pseudo</label>
                <input type="text" id="pseudo" name="pseudo" required>
            </div>
            <div class="form-group2">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group2">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group2">
                <label for="Nom">Nom</label>
                <input type="text" id="Nom" name="Nom" required>
            </div>
            <div class="form-group2">
                <label for="Prenom">Prenom</label>
                <input type="text" id="Prenom" name="Prenom" required>
            </div>
            
            <input type="submit" value="Inscription" class="custom-button2">
        </form>
    </div>
    HTML;
        } else {
            $pseudo = $_POST['pseudo'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $nom = $_POST['Nom'];
            $prenom = $_POST['Prenom'];
            
            $Auth = new Auth();
            if($Auth->register($pseudo, $email, $password,$nom,$prenom)){
                $html = <<<HTML
                <nav>
                    <button class="navi" onclick="window.location.href='index.php?action=DefaultAction'">Touiter</button>
                </nav>
                <p>Vous êtes bien inscrit </p>
                <button class="btnConnection" onclick="window.location.href='index.php?action=connection'">Connection</button>
            HTML;
            }else{
                $html = <<<HTML
                <nav>
                    <button class="navi" onclick="window.location.href='index.php?action=DefaultAction'">Touiter</button>
                </nav>
                <p>Erreur lors de l'inscription</p>
                <button class="btnConnection" onclick="window.location.href='index.php?action=inscription'">Inscription</button>
            HTML;
            }

            
            
            
            
            
            
            
        }
        return $html;
    }
}