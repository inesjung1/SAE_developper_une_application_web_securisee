<?php
declare(strict_types=1);
namespace Iutncy\Sae\Action;
use Iutncy\Sae\Db\ConnectionFactory;
use Iutncy\Sae\Touites\ListTouite;
use Iutncy\Sae\Touites\Touite;
use Iutncy\Sae\User\User;
use Iutncy\Sae\Render\TouiteRenderer;
use Iutncy\Sae\Render\ListTouiteRenderer;
use Iutncy\Sae\Render\Renderer;
class DefaultAction extends Action {
    public function __construct() {}
    public function execute(): string {
        $db = ConnectionFactory::makeConnection();
        $sql = "SELECT * FROM Touite INNER JOIN Utilisateur ON Touite.UtilisateurID = Utilisateur.UtilisateurID ORDER BY datePublication DESC";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $touites = $stmt->fetchAll();
        $html = <<<HTML
            <nav>
                <button class="navi" onclick="window.location.href='index.php?action=DefaultAction'">Touiter</button>
                <form action="index.php?action=recherche" method="get">
                    <input class="navi" type="hidden" value="recherche" name="action">
                    <input class="entreeTexte navi" type="text" name="recherche" placeholder="Recherche">
                    <input class="entreeButton navi" type="submit" value="Recherche">
                </form>
                <button class="navi" onclick="window.location.href='index.php?action=connection'">Connexion</button>
                <button class="navi" onclick="window.location.href='index.php?action=inscription'">Inscription</button>
                <button class="navi" onclick="window.location.href='index.php?action=utilisateuraction'">Touitez</button>
            </div>
            HTML;
        $liTouite = new ListTouite();
        foreach ($touites as $touite) {

            $email = $touite['AdresseEmail'];
            $pseudo = $touite['PSEUDO'];
            $mdp = $touite['MDP'];
            $user = new User($email, $pseudo, $mdp);

            $texte = $touite['Texte'];
            $date = $touite['DatePublication'];

            //On extrait les tags dans la table ContientTag
            $db2 = ConnectionFactory::makeConnection();
            $sql2 = "SELECT * FROM Contienttag WHERE TouiteID = $touite[TouiteID]";
            $stmt2 = $db2->prepare($sql2);
            $stmt2->execute();
            $tag = $stmt2->fetchAll();
            $litag = [];
            foreach ($tag as $t) {
                $litag[] = $t['TagID'];
            }

            $t = new Touite($texte, $date, $user, $litag);
            $liTouite -> addTouite($t);
            $affiche = new TouiteRenderer($t,$touite['UtilisateurID']);
            $act = $_SERVER['QUERY_STRING'];
            $html .= $affiche->render(2, $act);
        }
        //$affiche = new ListTouiteRenderer($liTouite);
        //$html .= $affiche->render();
        return $html;
    }
}