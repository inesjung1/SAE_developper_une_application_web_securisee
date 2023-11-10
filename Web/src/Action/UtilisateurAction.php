<?php
namespace Iutncy\Sae\Action;
use Iutncy\Sae\Db\ConnectionFactory;
use Iutncy\Sae\Touites\ListTouite;
use Iutncy\Sae\Touites\Touite;
use Iutncy\Sae\User\User;
use Iutncy\Sae\Render\TouiteRenderer;

class UtilisateurAction extends Action
{
    public function __construct()
    {
        parent::__construct();
    }


    public function execute(): string
    {
        if (!isset($_COOKIE['user'])) {
            setcookie('user', "0", time() + 3600, '/');
        }
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
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
            <button class="navi" onclick="window.location.href='index.php?action=MesStatistiques'">Mes Statistiques</button>
            <button class="navi2" id="btnEcrireTouite" onclick="montrerZoneDeTexte()">Écrire Touite</button>
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
            $sql = "SELECT * FROM Utilisateur WHERE AdresseEmail = '" . $_GET['user'] . "';";
            $sql2 = "SELECT * FROM Utilisateur WHERE UtilisateurID = '" . $_GET['user'] . "';";
            $stmt2 = $db->prepare($sql2);
            $stmt = $db->prepare($sql);
            $stmt2->execute();
            $stmt->execute();
            if ($_COOKIE['user'] == $stmt2->fetch()['UtilisateurID']) {
                $html = <<<HTML
        <button id="btnEcrireTouite" onclick="montrerZoneDeTexte()">Écrire Touite</button>
        <div id="zoneDeTexteTouite" style="display:none;">
        <div class="center-text">
            <form action="index.php?action=UtilisateurAction&user={$_GET['user']}" method="post" enctype="multipart/form-data">
                <textarea name="texteTouite" placeholder="Quoi de neuf?" maxlength="235"></textarea>
                <input type="file" name="imageTouite" accept="image/*" />
                <input type="submit" value="Publier Touite" />
            </form>
        </div>
        </div>
        <script>
        function montrerZoneDeTexte() {
            var zone = document.getElementById('zoneDeTexteTouite');
            var btn = document.getElementById('btnEcrireTouite');
            if (zone.style.display === "none") {
                zone.style.display = "block";
                btn.innerText = "Annuler";
            } else {
                zone.style.display = "none";
                btn.innerText = "Écrire Touite";
            }
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
        $db = ConnectionFactory::makeConnection();
        $imageID = null;

        if (isset($_FILES['imageTouite']) && $_FILES['imageTouite']['error'] == UPLOAD_ERR_OK) {
            $filename = basename($_FILES['imageTouite']['name']);
            $uploadDir = 'src/Image/';
            $uploadFile = $uploadDir . $filename;
            if (getimagesize($_FILES['imageTouite']['tmp_name']) === false) {
                echo "Le fichier n'est pas une image.";
                return;
            }
            if (file_exists($uploadFile)) {
                echo "Désolé, le fichier existe déjà.";
                return;
            }
            if (move_uploaded_file($_FILES['imageTouite']['tmp_name'], $uploadFile)) {
                $sqlImage = "INSERT INTO Image (Description, CheminFichier) VALUES (:description, :chemin)";
                $stmtImage = $db->prepare($sqlImage);
                $stmtImage->bindValue(':description', $filename);
                $stmtImage->bindValue(':chemin', $uploadFile);
                if ($stmtImage->execute()) {
                    $imageID = $db->lastInsertId();
                } else {
                    echo "Erreur lors de l'enregistrement de l'image dans la base de données.";
                    return;
                }
            } else {
                echo "Erreur lors de l'upload de l'image.";
                return;
            }
        }

        if (!empty($texteTouite)) {
            $sqlTouite = "INSERT INTO Touite (Texte, ImageID, DatePublication, UtilisateurID) VALUES (:texte, :imageID, NOW(), :UtilisateurID)";
            $stmtTouite = $db->prepare($sqlTouite);
            $stmtTouite->bindValue(':texte', base64_encode($texteTouite));
            $stmtTouite->bindValue(':imageID', $imageID, \PDO::PARAM_INT);
            $stmtTouite->bindValue(':UtilisateurID', $_GET['user']);

            if (!$stmtTouite->execute()) {
                echo "Erreur lors de l'ajout du touite.";
                print_r($stmtTouite->errorInfo());
            } else {
                header('Location: index.php?action=UtilisateurAction&user=' . $_GET['user']);
                exit;
            }
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