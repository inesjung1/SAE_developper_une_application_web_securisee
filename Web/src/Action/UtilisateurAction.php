<?php
namespace Iutncy\Sae\Action;
use Iutncy\Sae\Db\ConnectionFactory;
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
                <button class="navi" onclick="window.location.href='index.php?action=MesAbonnesAction'">Mes Abonnés</button>
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
            <form action="index.php?action=UtilisateurAction&user={$_GET['user']}" method="post" enctype="multipart/form-data">
                <textarea name="texteTouite" placeholder="Quoi de neuf?" maxlength="235"></textarea>
                <input type="file" name="imageTouite" accept="image/*" />
                <input type="submit" value="Publier Touite" />
            </form>
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
            $stmtTouite->bindValue(':texte', $texteTouite);
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
        $sql = "SELECT Touite.*, Image.CheminFichier FROM Touite
            LEFT JOIN Image ON Touite.ImageID = Image.ImageID
            WHERE Touite.UtilisateurID = :userID ORDER BY datePublication DESC";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':userID', $_GET['user']);
        $stmt->execute();
        $touites = $stmt->fetchAll();
        $html = '';

        foreach ($touites as $touite) {
            $html .= '<div class="touite">';
            $html .= '<p>' . htmlspecialchars($touite['Texte']) . '</p>';
            $html .= '<p>Publié le : ' . htmlspecialchars($touite['DatePublication']) . '</p>';
            if (!empty($touite['CheminFichier'])) {
                $html .= '<img src="' . htmlspecialchars($touite['CheminFichier']) . '" alt="Image du touite" width="150" height="150"/>';
            }
            $html .= '</div>';
        }
        return $html;
    }

}