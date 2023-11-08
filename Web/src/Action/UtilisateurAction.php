<?php
namespace Iutncy\Sae\Action;
use Iutncy\Sae\Db\ConnectionFactory;
use Iutncy\Sae\Touites\Touite;
use Iutncy\Sae\User\User;
use Iutncy\Sae\Touites\ListTouite;
use Iutncy\Sae\Render\TouiteRenderer;
class UtilisateurAction extends Action
{
        public function __construct()
        {
        parent::__construct();
        }

    public function execute(): string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $html = <<<HTML
                <button class="navi" onclick="window.location.href='index.php?action=DefaultAction'">Touiter</button>
            HTML;
            echo $html;
            echo $this->afficherFormulaireTouite();
            echo $this->afficherTouites();
        } else {
            $this->traiterTouite();
        }
        return '';
    }

    private function afficherFormulaireTouite(): string {
        if (isset($_COOKIE['user'])) {
            $db = ConnectionFactory::makeConnection();
            $sql = "SELECT * FROM Utilisateur WHERE AdresseEmail = ".$_GET['user'].";";
            $sql2 = "SELECT * FROM Utilisateur WHERE UtilisateurID = ".$_GET['user'].";";
            $stmt2 = $db->prepare($sql2);
            $stmt = $db->prepare($sql);
            $stmt2->execute();
            $stmt->execute();
            if ($_COOKIE['user'] == $stmt2->fetch()['UtilisateurID']) {
                $html = <<<HTML
                <button id="btnEcrireTouite" onclick="montrerZoneDeTexte()">Écrire Touite</button>
                <div id="zoneDeTexteTouite" style="display:none;">
                    <form action="index.php?action=UtilisateurAction&user=
                HTML;
                $html .= $_GET['user'];
                $html .= <<<HTML
                    " method="post">
                        <textarea name="texteTouite" placeholder="Quoi de neuf?" maxlength="235"></textarea>
                        <input type="submit" value="Publier Touite">
                    </form>
                </div>
                <script>
                function montrerZoneDeTexte() {
                    var zone = document.getElementById('zoneDeTexteTouite');
                    zone.style.display = zone.style.display === "none" ? "block" : "none";
                }
                </script>
                HTML;
                return $html;
            }
        }
        return '';
    }

    private function traiterTouite(): void
    {
        $texteTouite = $_POST['texteTouite'] ?? '';
        if (!empty($texteTouite)) {
            $db = ConnectionFactory::makeConnection();
            $sql = "INSERT INTO Touite (Texte, datePublication, UtilisateurID) VALUES (:texte, :datePublication, :UtilisateurID)";
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':texte', base64_encode($texteTouite));
            $stmt->bindValue(':datePublication', date("Y-m-d H:i:s"));
            $stmt->bindValue(':UtilisateurID', $_GET['user']);
            $stmt->execute();

            header('Location: index.php?action=UtilisateurAction&user='.$_GET['user']);
            exit;
        } else {
            echo "Aucun texte fourni pour le touite.";
        }
    }

    private function afficherTouites(): string {
        $db = ConnectionFactory::makeConnection();
        $sql = "SELECT * FROM Touite INNER JOIN Utilisateur ON Touite.UtilisateurID = Utilisateur.UtilisateurID
                    WHERE Touite.UtilisateurID='".$_GET['user']."' ORDER BY datePublication DESC";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $touites = $stmt->fetchAll();
        $html = '';
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
        return $html;
    }
}