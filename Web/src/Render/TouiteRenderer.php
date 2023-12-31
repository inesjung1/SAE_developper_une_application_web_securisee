<?php
declare(strict_types=1);
namespace Iutncy\Sae\Render;
use Iutncy\Sae\Touites\Touite;
use Iutncy\Sae\db\ConnectionFactory;

class TouiteRenderer implements Renderer{
    private Touite $touite;
    public function __construct(Touite $touite) {
        $this->touite = $touite;
    }
    public function render(int $selector, string $aaction): string {
        $action = base64_encode($aaction);
        $touite = $this->touite;
        $user = $touite->getAuteur();
        $pseudo = $user->getPseudo();
        $pseudo = htmlspecialchars($pseudo);
        $email = $user->getEmail();
        $email = htmlspecialchars($email);
        $idU = $user->getId();
        $content = base64_decode($touite->getTexte());
        $content = htmlspecialchars($content);
        $date = $touite->getDate();
        $date = htmlspecialchars($date);
        $idT = $touite->getId();
        $db = ConnectionFactory::makeConnection();
        $sql = "SELECT LOVE, DISLOVE FROM Touite WHERE TouiteID = $idT;";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $stmt2 = $db->prepare($sql);
        $stmt2->execute();
        $love = $stmt->fetch()['LOVE'];
        $dislove = $stmt2->fetch()['DISLOVE'];
        $tag = '';
        $sql5 = "SELECT COUNT(*) FROM contienttag WHERE TouiteID = $idT;";
        $stmt5 = $db->prepare($sql5);
        $stmt5->execute();
        $count = $stmt5->fetch()['COUNT(*)'];
        if($count != 0){
            $sql4 = "SELECT LIBELLE, tag.TagID FROM tag
            INNER JOIN contienttag ON contienttag.TagID = tag.TagID
            WHERE contienttag.TouiteID = $idT;";
            $stmt4 = $db->prepare($sql4);
            $stmt4->execute();
            $v = $stmt4->fetchAll()[0];
            $tag = $v['LIBELLE'];
            $tag = htmlspecialchars($tag);
            $tagID = $v['TagID'];
        }
        $monId = $_COOKIE['user'];
        //On récupère l'image si elle existe
        $sql10 = "SELECT CheminFichier FROM Touite
        LEFT JOIN Image ON Touite.ImageID = Image.ImageID
         WHERE TouiteID = $idT;";
        $stmt10 = $db->prepare($sql10);
        $stmt10->execute();
        $image = $stmt10->fetchAll();
        $image = $image[0]['CheminFichier'];

        switch ($selector) {
            case self::LONG:
                $html = <<<HTML
                <div class="touite"  >
                    <div class="touite-header">
                        <div class="touite-pseudo">
                        <a class="user" href="index.php?action=UtilisateurAction&user=$idU">$pseudo</a>
                HTML;
                if(($_COOKIE['user'] != 0)&&($idU != $monId)){
                    $sql3 = "SELECT COUNT(*) FROM abonnement WHERE AbonneUtilisateurID = $monId AND SuiviUtilisateurID = $idU;";
                    $stmt3 = $db->prepare($sql3);
                    $stmt3->execute();
                    $abonnements = $stmt3->fetchAll();
                    $count = $abonnements[0]['COUNT(*)'];
                    //on verifie que l'utilisateur n'est pas deja abonné
                    if($count == 0) {
                        $html .= <<<HTML
                        <button id="abonnement" onclick="window.location.href='index.php?action=Sabonner&id=$idT&idU=$monId&aaction=$action'">S'abonner</button>
                        HTML;
                    }else{
                        $html .= <<<HTML
                        <button id="abonnement" onclick="window.location.href='index.php?action=SeDesabonner&id=$idT&idU=$monId&aaction=$action'">Se désabonner</button>
                        HTML;
                    }
                }
                $html .= <<<HTML
                        </div>
                        <div class="touite-email">$email</div>
                        <div class="touite-date">$date</div>
                    </div>
                    <div class="touite-content">$content</div>
                HTML;
                //On affiche l'image si elle existe
                if (!empty($image)) {
                    $html .= '<div style="text-align: center;">'; // Ajoutez cette div pour centrer le contenu
                    $html .= '<img src="' . htmlspecialchars($image) . '" alt="Image du touite" width="550" height="350"/>';
                    $html .= '</div>';
                }
                // On affiche le tag si il existe

                if ($tag != ''){
                    $sql6 = "SELECT COUNT(*) FROM abonnementtag WHERE AbonneUtilisateurID = $monId AND abonnementtag.TagID = $tagID;";
                    $stmt6 = $db->prepare($sql6);
                    $stmt6->execute();
                    $abonnements = $stmt6->fetchAll();
                    $count = $abonnements[0]['COUNT(*)'];
                    $html .= <<<HTML
                        <div class="touite-tag"><a class="user" href="index.php?action=recherche&recherche=$tag">#$tag</a></div>
                    HTML;
                    //on verifie que l'utilisateur n'est pas deja abonné
                    if($count == 0) {
                        $html .= <<<HTML
                        <button id="aTag" onclick="window.location.href='index.php?action=SabonnerTag&id=$idT&idU=$monId&aaction=$action'">S'abonner</button>
                        HTML;
                    }else{
                        $html .= <<<HTML
                        <button id="aTag" onclick="window.location.href='index.php?action=SeDesabonnerTag&id=$idT&idU=$monId&aaction=$action'">Se désabonner</button>
                        HTML;
                    }
                }
                    if ($_COOKIE['user']!=0){
                        $html .= <<<HTML
                        <button id="love" onclick="window.location.href='index.php?action=loveaction&id=$idT&idU=$monId&aaction=$action'">❤️ : $love</button>
                        <button id="dislove" onclick="window.location.href='index.php?action=disloveaction&id=$idT&idU=$monId&aaction=$action'">💔: $dislove</button>
                        <button id="afficher" onclick="window.location.href='index.php?action=AfficherTouiteAction&id=$idT&aaction=$action'">afficher</button>
                        HTML;
                    }else{
                        $html .= <<<HTML
                        <button id="love">❤️ : $love</button>
                        <button id="dislove">💔: $dislove</button>
                        <button id="afficher" onclick="window.location.href='index.php?action=AfficherTouiteAction&id=$idT&aaction=$action'">afficher</button>

                        HTML;
                    }
                    $html .= <<<HTML
                    </div> <br>
                    HTML;
                break;
            case self::COMPACT:
                $html = <<<HTML
                    <div class="touite">
                    <a class="user" href="index.php?action=UtilisateurAction&user=$idU">@$pseudo</a>
                HTML;
                if(($_COOKIE['user'] != 0)&&($idU != $monId)){
                    $sql3 = "SELECT COUNT(*) FROM abonnement WHERE AbonneUtilisateurID = $monId AND SuiviUtilisateurID = $idU;";
                    $stmt3 = $db->prepare($sql3);
                    $stmt3->execute();
                    $abonnements = $stmt3->fetchAll();
                    $count = $abonnements[0]['COUNT(*)'];
                    //on verifie que l'utilisateur n'est pas deja abonné
                    if($count == 0) {
                        $html .= <<<HTML
                        <button id="abonnement" onclick="window.location.href='index.php?action=Sabonner&id=$idT&idU=$monId&aaction=$action'">S'abonner</button>
                        HTML;
                    }else{
                        $html .= <<<HTML
                        <button id="abonnement" onclick="window.location.href='index.php?action=SeDesabonner&id=$idT&idU=$monId&aaction=$action'">Se désabonner</button>
                        HTML;
                    }
                }else if($_COOKIE['user'] != 0){
                    $html .= <<<HTML
                    <button id="delete" onclick="window.location.href='index.php?action=DeleteTouite&idTouite=$idT&aaction=$action'">Supprimer</button>
                    HTML;
                }
                $html .= <<<HTML
                    <div class="touite-content">$content</div>
                HTML;
                if (!empty($image)) {
                    $html .= '<div style="text-align: center;">'; // Ajoutez cette div pour centrer le contenu
                    $html .= '<img src="' . htmlspecialchars($image) . '" alt="Image du touite" width="250" height="150"/>';
                    $html .= '</div>';
                }
                if ($tag != ''){
                    $sql6 = "SELECT COUNT(*) FROM abonnementtag WHERE AbonneUtilisateurID = $monId AND abonnementtag.TagID = $tagID;";
                    $stmt6 = $db->prepare($sql6);
                    $stmt6->execute();
                    $abonnements = $stmt6->fetchAll();
                    $count = $abonnements[0]['COUNT(*)'];
                    $html .= <<<HTML
                        <div class="touitetag">
                        <a class="touitetag" href="index.php?action=recherche&recherche=$tag">#$tag</a>
                        </div>
                    HTML;
                    //on verifie que l'utilisateur n'est pas deja abonné
                    if($count == 0) {
                        $html .= <<<HTML
                        <button id="aTag" onclick="window.location.href='index.php?action=SabonnerTag&id=$idT&idU=$monId&aaction=$action'">S'abonner au #Tag</button>
                        HTML;
                    }else{
                        $html .= <<<HTML
                        <button id="aTag" onclick="window.location.href='index.php?action=SeDesabonnerTag&id=$idT&idU=$monId&aaction=$action'">Se désabonner du #Tag</button>
                        HTML;
                    }
                }
                if ($_COOKIE['user']!=0){
                    $html .= <<<HTML
                        <button id="love" onclick="window.location.href='index.php?action=loveaction&id=$idT&idU=$monId&aaction=$action'">❤️ : $love</button>
                        <button id="dislove" onclick="window.location.href='index.php?action=disloveaction&id=$idT&idU=$monId&aaction=$action'">💔 : $dislove</button>
                        <button id="afficher" onclick="window.location.href='index.php?action=AfficherTouiteAction&id=$idT&aaction=$action'">+</button>
                        HTML;
                }else{
                    $html .= <<<HTML
                        <button id="love">❤️: $love</button>
                        <button id="dislove">💔: $dislove</button>
                        <button id="afficher" onclick="window.location.href='index.php?action=AfficherTouiteAction&id=$idT&aaction=$action'">+</button>
                        HTML;
                }
                $html .= <<<HTML
                    </div> <br>
                    HTML;

        }
        return $html;
    }
}