<?php
namespace Iutncy\Sae\Action;

class UtilisateurAction extends Action
{
        public function __construct()
        {
        parent::__construct();
        }

    public function execute(): string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $navbar = <<<NAVBAR
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
            NAVBAR;
            echo $navbar;
            echo $this->afficherTouites();
            echo $this->afficherFormulaireTouite();
        } else {
            $this->traiterTouite();
        }
        return '';
    }

    private function afficherFormulaireTouite(): string
    {
        $html = <<<HTML
        <button id="btnEcrireTouite" onclick="montrerZoneDeTexte()">Écrire Touite</button>
        <div id="zoneDeTexteTouite" style="display:none;">
            <form action="{$_SERVER['PHP_SELF']}?action=utilisateuraction" method="post">
                <textarea name="texteTouite" placeholder="Quoi de neuf?"></textarea>
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

    private function traiterTouite(): void
    {
        $texteTouite = $_POST['texteTouite'] ?? '';
        if (!empty($texteTouite)) {
            $db = \Iutncy\Sae\Db\ConnectionFactory::makeConnection();
            $sql = "INSERT INTO Touite (Texte, datePublication, UtilisateurID) VALUES (:texte, :datePublication, :UtilisateurID)";
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':texte', $texteTouite);
            $stmt->bindValue(':datePublication', date("Y-m-d H:i:s"));
            $stmt->bindValue(':UtilisateurID', 1);
            $stmt->execute();

            header('Location: '.$_SERVER['PHP_SELF'].'?action=utilisateuraction');
            exit;
        } else {
            echo "Aucun texte fourni pour le touite.";
        }
    }

    private function afficherTouites(): string
    {
        $showTouite = new ShowTouite();
        return $showTouite->execute();
    }
}




