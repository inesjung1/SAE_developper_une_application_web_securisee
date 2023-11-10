<?php

namespace Iutncy\Sae\Render;

use Iutncy\Sae\Db\ConnectionFactory;
use Iutncy\Sae\Utilisateur\User;

class UtilRenderer implements Renderer
{
    private int $utilisateur;

    public function __construct(int $utilisateur) {
        $this->utilisateur = $utilisateur;
    }

    public function render(int $selector, string $aaction): string {
        //récupérer les infos de l'utilisateur
        $db = ConnectionFactory::makeConnection();
        $sql = "SELECT * FROM Utilisateur WHERE UtilisateurID = '$this->utilisateur'";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $res = $stmt->fetch();
        $pseudo = $res['PSEUDO'];
        $email = $res['AdresseEmail'];
        $idU = (string) $res['UtilisateurID'];
        $html = <<<HTML
                <div class="touite"  >
                    <div class="touite-header">
                        <div class="touite-pseudo">
                        <a class="user" href="index.php?action=UtilisateurAction&user=$idU">#$pseudo</a>
                HTML;
        $html .= <<<HTML
                        </div>
                        <div class="touite-email">$email</div>
                    </div>
                HTML;
        return $html;
    }


}