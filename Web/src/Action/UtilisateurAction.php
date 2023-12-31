<?php
namespace Iutncy\Sae\Action;
use Iutncy\Sae\Db\ConnectionFactory;
use Iutncy\Sae\Render\UtilRenderer;
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
        $html = '';
        if (!isset($_COOKIE['user'])) {
            setcookie('user', "0", time() + 3600, '/');
        }
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $idU = $_COOKIE['user'];
            $id2 = $_GET['user'];
            $html = <<<HTML
                <nav>
                <button class="navi" onclick="window.location.href='index.php?action=DefaultAction'">Accueil</button>
                <form action="index.php?action=recherche" method="get">
                    <input class="navi" type="hidden" value="recherche" name="action">
                    <input class="entreeTexte navi" type="text" name="recherche" placeholder="Recherche #Tag">
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
            $affiche = New UtilRenderer($id2);
            $af = $affiche->render(1, "UtilisateurAction");
            $html .= <<<HTML
                <div class="touite-header">
                    <div class="touite-pseudo">
                    $af
                    </div>
                </div>
            HTML;
            $html .= $this->afficherFormulaireTouite();
            $html .= $this->afficherTouites();
        } else {
            $this->traiterTouite();
        }
        return $html;
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
        <button id="EcrireTouite" onclick="montrerZoneDeTexte()">Écrire Touite</button>
        <div id="zoneDeTexteTouite" style="display:none;">
        <div class="centertext">
            <form action="index.php?action=UtilisateurAction&user={$_GET['user']}" method="post" enctype="multipart/form-data">
                <textarea name="texteTouite" placeholder="Quoi de neuf?" maxlength="235"></textarea>
                <textarea name="texteTag" placeholder="On rajoute un tag ?"></textarea>
               <label for="imageTouite" class="custom-file-upload">
                Choisir un fichier
                <input type="file" name="imageTouite" id="imageTouite" accept="image/*" style="display:none;">
                </label>
                <input type="submit" value="Publier Touite" />
            </form>
        </div>
        </div>
        <script>
         function montrerZoneDeTexte() {
        var zoneTexte = document.getElementById('zoneDeTexteTouite');
        var zoneTouites = document.getElementById('zoneTouites');
        var btnEcrireTouite = document.getElementById('btnEcrireTouite');
        if (zoneTexte.style.display === "none") {
            zoneTexte.style.display = "block";
            zoneTouites.style.display = "none";
            btnEcrireTouite.innerText = "Annuler";
        } else {
            zoneTexte.style.display = "none";
            zoneTouites.style.display = "block";
            btnEcrireTouite.innerText = "Écrire Touite";
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
        $texteTag = $_POST['texteTag'] ?? '';
        $db = ConnectionFactory::makeConnection();
        $imageID = null;

        if (isset($_FILES['imageTouite']) && $_FILES['imageTouite']['error'] == UPLOAD_ERR_OK) {
            $nomFichier = basename($_FILES['imageTouite']['name']);
            $repertoireTelechargement = 'src/Image/';
            $cheminFichier = $repertoireTelechargement . $nomFichier;

            $sqlVerifFichier = "SELECT CheminFichier FROM Image WHERE Description = :description";
            $stmtVerifFichier = $db->prepare($sqlVerifFichier);
            $stmtVerifFichier->bindValue(':description', $nomFichier);
            $stmtVerifFichier->execute();
            $fichierExistant = $stmtVerifFichier->fetchColumn();

            if ($fichierExistant) {
                $nouveauNomFichier = $this->nomUnique($repertoireTelechargement, $nomFichier);
                $cheminFichier = $repertoireTelechargement . $nouveauNomFichier;

                if (move_uploaded_file($_FILES['imageTouite']['tmp_name'], $cheminFichier)) {
                    $sqlImage = "INSERT INTO Image (Description, CheminFichier) VALUES (:description, :chemin)";
                    $stmtImage = $db->prepare($sqlImage);
                    $stmtImage->bindValue(':description', $nouveauNomFichier);
                    $stmtImage->bindValue(':chemin', $cheminFichier);

                    if ($stmtImage->execute()) {
                        $imageID = $db->lastInsertId();
                    } else {
                        echo "Erreur lors de l'enregistrement de l'image dans la base de données.";
                        return;
                    }
                } else {
                    echo "Erreur lors du déplacement du fichier.";
                    return;
                }
            }
        }

        if (!empty($texteTouite)) {
            $date = date('Y-m-d');
            $sqlTouite = "INSERT INTO Touite (Texte, ImageID, DatePublication, UtilisateurID) VALUES (:texte, :imageID, :date, :UtilisateurID)";
            $stmtTouite = $db->prepare($sqlTouite);
            $stmtTouite->bindValue(':texte', base64_encode($texteTouite));
            $stmtTouite->bindValue(':imageID', $imageID, \PDO::PARAM_INT);
            $stmtTouite->bindValue(':date', $date);
            $stmtTouite->bindValue(':UtilisateurID', $_GET['user']);

            if (!$stmtTouite->execute()) {
                echo "Erreur lors de l'ajout du touite.";
                print_r($stmtTouite->errorInfo());
            } else {

                if(!empty($texteTag)){
                    $sql1 = "SELECT count(*) from tag where libelle=:tag;";
                    $stmt1 = $db->prepare($sql1);
                    $stmt1->bindValue(':tag', $texteTag);
                    $stmt1->execute();
                    $res = $stmt1->fetch()['count(*)'];
                    if($res==0){
                        $sql2 = "INSERT INTO tag (libelle,description) VALUES (:libelle,:description)";
                        $stmt2 = $db->prepare($sql2);
                        $stmt2->bindValue(':libelle', $texteTag);
                        $stmt2->bindValue(':description', $texteTag);
                        $stmt2->execute();
                    }

                    $sql3 = "SELECT tagID from tag where libelle=:texte;";
                    $stmt3 = $db->prepare($sql3);
                    $stmt3->bindValue(':texte', $texteTag);
                    $stmt3->execute();
                    $resTag = $stmt3->fetchAll();
                    $idTag = $resTag[0]['tagID'];

                    $sqL4 = "SELECT max(TouiteID) from touite where UtilisateurID=:uid;";
                    $stmt4 = $db->prepare($sqL4);
                    $stmt4->bindValue(':uid', $_GET['user']);
                    $stmt4->execute();
                    $resTouite = $stmt4->fetchAll();
                    $idTouite = $resTouite[0]['max(TouiteID)'];

                    $sql5 = "INSERT INTO contientTag (touiteID, tagID) VALUES (:touite, :tag)";
                    $stmt5 = $db->prepare($sql5);
                    $stmt5->bindValue(':touite', $idTouite);
                    $stmt5->bindValue(':tag', $idTag);
                    $stmt5->execute();
                }
                header('Location: index.php?action=UtilisateurAction&user=' . $_GET['user']);
                exit;
            }
        } else {
            echo "Aucun texte fourni pour le touite.";
        }
    }

    private function nomUnique($repertoireTelechargement, $nomFichier): string
    {
        $extension = pathinfo($nomFichier, PATHINFO_EXTENSION);
        $nomBase = pathinfo($nomFichier, PATHINFO_FILENAME);
        $nouveauNomFichier = $nomBase . "_" . time() . "." . $extension;
        while (file_exists($repertoireTelechargement . $nouveauNomFichier)) {
            $nouveauNomFichier = $nomBase . "_" . time() . "." . $extension;
        }
        return $nouveauNomFichier;
    }

    private function DescriptionImage($description) {
        $db = ConnectionFactory::makeConnection();
        $sql = "SELECT ImageID FROM Image WHERE Description = :description";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':description', $description);
        $stmt->execute();
        $imageID = $stmt->fetchColumn();
        return $imageID;
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
            $html .= $affiche->render(1, $act);
        }
        return $html;
    }


}