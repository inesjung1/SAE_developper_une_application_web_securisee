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
            <div class="default">
                <form action="index.php?action=recherche" method="get">
                    <input type="hidden" value="recherche" name="action">
                    <input class="entreeTexte" type="text" id="recherche" name="recherche" placeholder="Recherche">
                    <input class="entreeButton" type="submit" value="Recherche">
                </form>
                <button class="btnConnection" onclick="window.location.href='index.php?action=connection'">Connection</button>
                <button class="btnInscription" onclick="window.location.href='index.php?action=inscription'">Inscription</button>
            </div>
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