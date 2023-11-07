<?php
declare(strict_types=1);
namespace Iutncy\Sae\Action;
use Iutncy\Sae\Touites\ListTouite;
use Iutncy\Sae\Db\ConnectionFactory;
class RechercheAction extends Action {
    public function __construct() {}
    public function execute(): string {
        $recherche = $_GET['recherche'];
        $listRecherche = new ListTouite();
        $db = ConnectionFactory::makeConnection();
        $sql = "SELECT * FROM Touite INNER JOIN Utilisateur ON Touite.UtilisateurID = Utilisateur.UtilisateurID 
                INNER JOIN contienttag ON touite.TouiteID = contienttag.TouiteID 
                INNER JOIN tag on contienttag.TagID = tag.TagID
                    WHERE tag.Libelle ='".$_GET['recherche']."' ORDER BY datePublication DESC";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $touites = $stmt->fetchAll();
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
        HTML;foreach ($touites as $touite) {
            $html .= '
            <div class="touiteContainer">
                <a class="user" href="index.php?action=UtilisateurAction&user='.$touite["UtilisateurID"].'">' . $touite['PSEUDO'] . '</a>
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