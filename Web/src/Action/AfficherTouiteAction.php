<?php
declare(strict_types=1);
namespace Iutncy\Sae\Action;
use Iutncy\Sae\Db\ConnectionFactory;
use Iutncy\Sae\Touites\Touite;
use Iutncy\Sae\render\TouiteRenderer;
use Iutncy\Sae\User\User;

class AfficherTouiteAction extends Action {
    public function __construct()
    {
    }
    public function execute(): string
    {
        if (!isset($_COOKIE['user'])) {
            setcookie('user', "0", time() + 3600, '/');
        }
        $idU = $_COOKIE['user'];
        $html = <<<HTML
                <nav>
                <button class="navi" onclick="window.location.href='index.php?action=DefaultAction'">Touiter</button>
                <form action="index.php?action=recherche" method="get">
                    <input class="navi" type="hidden" value="recherche" name="action">
                    <input class="entreeTexte navi" type="text" name="recherche" placeholder="Recherche">
                    <input class="entreeButton navi" type="submit" value="Recherche">
                </form>
        HTML;
        if ($_COOKIE['user'] != 0) {
            $html .= <<<HTML
            <button class="navi" onclick="window.location.href='index.php?action=deconnexionaction'">Deconnexion</button>
            <button class="navi" onclick="window.location.href='index.php?action=UtilisateurAction&user=$idU'">Mon Profil</button>
            <button class="navi" onclick="window.location.href='index.php?action=AbonnementsAction'">Mes Abonnements</button>
            <button class="navi" onclick="window.location.href='index.php?action=AbonnementsTag'">Mes Tags</button>
            HTML;
        }else{
            $html .= <<<HTML
            <button class="navi" onclick="window.location.href='index.php?action=connection'">Connexion</button>
            <button class="navi" onclick="window.location.href='index.php?action=inscription'">Inscription</button>
            HTML;
        }
        $html .= <<<HTML
            </nav>
        HTML;
        $html .= <<<HTML
            <button class="retour" onclick="window.location.href='index.php?
        HTML;
        $html .= base64_decode($_GET['aaction']);
        $html .= <<<HTML
             '">Retour</button>
        HTML;

        $db= ConnectionFactory::makeConnection();
        $sql = "SELECT * FROM Touite INNER JOIN utilisateur ON Touite.UtilisateurID = utilisateur.UtilisateurID WHERE TouiteID = ".$_GET['id'].";";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $touite = $stmt->fetch();
        $db2 = ConnectionFactory::makeConnection();
        $sql2 = "SELECT * FROM Contienttag WHERE TouiteID = $touite[TouiteID]";
        $stmt2 = $db2->prepare($sql2);
        $stmt2->execute();
        $tag = $stmt2->fetchAll();
        $email = $touite['AdresseEmail'];
        $pseudo = $touite['PSEUDO'];
        $mdp = $touite['MDP'];
        $user = new User($email, $pseudo, $mdp);
        $litag = [];
        foreach ($tag as $t) {
            $litag[] = $t['TagID'];
        }
        $texte = $touite['Texte'];
        $date = $touite['DatePublication'];
        $t = new Touite($texte, $date, $user, $litag);
        $affiche = new TouiteRenderer($t,$touite['UtilisateurID']);
        $act = $_SERVER['QUERY_STRING'];
        $html .= $affiche->render(2, $act);
        return $html;
    }
}