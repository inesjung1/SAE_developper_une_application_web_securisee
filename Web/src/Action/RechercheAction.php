<?php
declare(strict_types=1);
namespace Iutncy\Sae\Action;
use Iutncy\Sae\Touites\ListTouite;
use Iutncy\Sae\Db\ConnectionFactory;
use Iutncy\Sae\User\User;
use Iutncy\Sae\Touites\Touite;
use Iutncy\Sae\Render\TouiteRenderer;
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
            <button class="navi" onclick="window.location.href='index.php?action=DefaultAction'">Touiter</button>
            <button class="btnConnection" onclick="window.location.href='index.php?action=connection'">Connection</button>
            <button class="btnInscription" onclick="window.location.href='index.php?action=inscription'">Inscription</button>
            <form action="index.php?action=recherche" method="get">
                <input type="hidden" value="recherche" name="action">
                <input type="text" id="recherche" name="recherche" placeholder="Recherche">
                <input type="submit" value="Recherche">
            </form>
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
            $html .= $affiche->render(2);
    }
        return $html;
}
}