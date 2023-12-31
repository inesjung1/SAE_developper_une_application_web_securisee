<?php
namespace Iutncy\Sae\Action;
class ShowTouite extends Action{
    // Constructeur qui initialise la connexion à la base de données
    public function __construct() {
        parent::__construct();
    }
    // Méthode qui affiche le touite sous forme de HTML
    public function execute(): string {
        if (!isset($_COOKIE['user'])) {
            setcookie('user', "0", time() + 3600, '/');
        }
        $html = '';
        $db = \Iutncy\Sae\Db\ConnectionFactory::makeConnection();
        $sql = "SELECT * FROM Touite";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $touites = $stmt->fetchAll();
        foreach ($touites as $touite) {
            $html .= '<div class="touite">';
            $html .= '<p>' . $touite['Texte'] .'</p>';
            $html .= '<p>' . $touite['DatePublication'] .'</p>';
            $html .= '<p>' . $touite['UtilisateurID'] .'</p>';
            $html .= '</div>';
        }
        return $html;
    }

}

