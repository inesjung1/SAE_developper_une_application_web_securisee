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
        $email = $user->getEmail();
        $idU = $user->getId();
        $content = base64_decode($touite->getTexte());
        $date = $touite->getDate();
        $idT = $touite->getId();
        $db = ConnectionFactory::makeConnection();
        $sql = "SELECT LOVE, DISLOVE FROM Touite WHERE TouiteID = $idT;";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $stmt2 = $db->prepare($sql);
        $stmt2->execute();
        $love = $stmt->fetch()['LOVE'];
        $dislove = $stmt2->fetch()['DISLOVE'];
        $monId = $_COOKIE['user'];
        switch ($selector) {
            case self::LONG:
                $html = <<<HTML
                <div class="touite">
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
                        <button id="abonnement" onclick="window.location.href='index.php?action=Sabonner&idU=$idU&id=$idT&aaction=$action'">S'abonner</button>
                        HTML;
                    }else{
                        $html .= <<<HTML
                        <button id="abonnement" onclick="window.location.href='index.php?action=SeDesabonner&idU=$idU&id=$idT&aaction=$action'">Se désabonner</button>
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
                    if ($_COOKIE['user']!=0){
                        $html .= <<<HTML
                        <button id="love" onclick="window.location.href='index.php?action=loveaction&id=$idT&idU=$monId&aaction=$action'">Love : $love</button>
                        <button id="dislove" onclick="window.location.href='index.php?action=disloveaction&id=$idT&idU=$monId&aaction=$action'">Dislove : $dislove</button>
                        HTML;
                    }else{
                        $html .= <<<HTML
                        <button id="love">Love : $love</button>
                        <button id="dislove">Dislove : $dislove</button>
                        HTML;
                    }
                    $html .= <<<HTML
                    </div> <br>
                    HTML;
                break;
            case self::COMPACT:
                $html = <<<HTML
                    <div class="touite">
                    <div class="touite-content">$content</div>
                    HTML;
                if ($_COOKIE['user']!=0){
                    $html .= <<<HTML
                        <button id="love" onclick="window.location.href='index.php?action=loveaction&id=$idT&idU=$monId&aaction=$action'">Love : $love</button>
                        <button id="dislove" onclick="window.location.href='index.php?action=disloveaction&id=$idT&idU=$monId&aaction=$action'">Dislove : $dislove</button>
                        HTML;
                }else{
                    $html .= <<<HTML
                        <button id="love">Love : $love</button>
                        <button id="dislove">Dislove : $dislove</button>
                        HTML;
                }
                $html .= <<<HTML
                    </div> <br>
                    HTML;

        }
        return $html;
    }
}