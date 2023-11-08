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
                <form action="index.php?action=inscription" method="post">
                    <label for="pseudo">Pseudo</label>
                    <input class="entreeTexte" type="text" id="pseudo" name="pseudo" required>
                    <label for="email">Email</label>
                    <input class="entreeTexte" type="email" id="email" name="email" required>
                    <label for="password">Mot de passe</label>
                    <input class="entreeTexte" type="password" id="password" name="password" required>
                    <input class="entreeButton" type="submit" value="Inscription">
                </form>
            HTML;
        } else {
            $pseudo = $_POST['pseudo'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $Auth = new Auth();
            if($Auth->register($pseudo, $email, $password)){
                $html = <<<HTML
                <p>Vous êtes bien inscrit</p>
                <button class="btnConnection" onclick="window.location.href='index.php?action=connection'">Connection</button>
            HTML;
            }else{
                $html = <<<HTML
                <p>Erreur lors de l'inscription</p>
                <button class="btnConnection" onclick="window.location.href='index.php?action=inscription'">Inscription</button>
            HTML;
            }

            
            
            
            
            
            
            
        }
        return $html;
    }
}