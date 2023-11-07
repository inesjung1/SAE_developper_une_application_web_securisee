<?php
declare(strict_types=1);
namespace Iutncy\Sae\Action;
use Iutncy\Sae\Db\ConnectionFactory;
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
            </nav>
            HTML;
        foreach ($touites as $touite) {
            $html .= '
            <div class="touiteContainer">
                <a class="user" href="index.php?action=affichertouite&user=1">' . $touite['PSEUDO'] . '</a>
                <p class="touite">' . $touite["Texte"] . '</p>
                <ul>
                    <li class="date">' . $touite["DatePublication"] . '</li>
                    <li class="love">'. $touite["LOVE"] .'</li>
                    <li class="love">'. $touite["DISLOVE"] .'</li>
                </ul>
            </div>
            <br>';
        }
        return $html;
    }
}