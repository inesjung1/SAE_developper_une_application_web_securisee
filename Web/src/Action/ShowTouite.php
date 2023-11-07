<?php
namespace Iutncy\Sae\Action;
class ShowTouite extends Action{
    // Constructeur qui initialise la connexion à la base de données
    public function __construct() {
        parent::__construct();
    }
    // Méthode qui affiche le touite sous forme de HTML
    public function execute(): string {
        $html = '';
        $db = \Iutncy\Sae\Db\ConnectionFactory::makeConnection();
        $sql = "SELECT * FROM Touite INNER JOIN Utilisateur ON Touite.UtilisateurID = Utilisateur.UtilisateurID ORDER BY datePublication DESC";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $touites = $stmt->fetchAll();
        foreach ($touites as $touite) {
            $html .= '<div class="touite">';
            $html .= '<p>' . $touite['PSEUDO'].'</p>';
            $html .= '<p>' . $touite['Texte'] . "adae".'</p>';
            $html .= '<p>' . $touite['DatePublication'] .'</p>';
            $html .= '</div>';
        }
        return $html;
    }

}

