<?php

namespace Iutncy\Sae\Action;

use Iutncy\Sae\Db\ConnectionFactory;

class DisloveAction extends Action
{
    public function __construct()
    {
    }

    public function execute(): string{
        if (!isset($_COOKIE['user'])) {
            setcookie('user', "0", time() + 3600, '/');
        }
        $db = ConnectionFactory::makeConnection();
        $idUtil = $_GET['idU'];
        $idTouite = $_GET['id'];
        $sql0 = "SELECT COUNT(*) FROM Opinion WHERE UtilisateurID = $idUtil AND TouiteID = $idTouite;";
        $stmt0 = $db->prepare($sql0);
        $stmt0->execute();
        $opinion = $stmt0->fetch();
        $count = $opinion['COUNT(*)'];
        if($count == 0) {
            $sql = "INSERT INTO Opinion (UtilisateurID, TouiteID, IsLove, IsDislove) VALUES ($idUtil, $idTouite, 0, 0);";
            $stmt = $db->prepare($sql);
            $stmt->execute();
        }
        $this->ajouterDisLikeTouite();
        $action = base64_decode($_GET['aaction']);
        header('Location: '.$_SERVER['PHP_SELF'].'?'.$action);
        return '';
    }

    public function ajouterDisLikeTouite()
    {
        $idUtil = $_GET['idU'];
        $id = $_GET['id'];
        $db = ConnectionFactory::makeConnection();
        $sql0 = "SELECT * FROM Opinion WHERE UtilisateurID = $idUtil AND TouiteID = $id;";
        $stmt0 = $db->prepare($sql0);
        $stmt0->execute();
        $opinion = $stmt0->fetch();
        $isLove = $opinion['IsLove'];
        $isDislove = $opinion['IsDislove'];
        $id = $_GET['id'];
        $sql = "SELECT * FROM Touite WHERE TouiteID = $id;";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $touite = $stmt->fetch();
        $dislove = $touite['DISLOVE'];

        if (($isLove == 0) && ($isDislove == 0)) {
            $dislove++;
            $sql2 = "UPDATE Touite SET DISLOVE = $dislove WHERE TouiteID = $id;";
            $stmt2 = $db->prepare($sql2);
            $stmt2->execute();
            $sql3 = "UPDATE Opinion SET IsDislove = 1 WHERE UtilisateurID = $idUtil AND TouiteID = $id;";
            $stmt3 = $db->prepare($sql3);
            $stmt3->execute();
        } else if (($isLove == 1) && ($isDislove == 0)) {
            $dislove++;
            $sql2 = "UPDATE Touite SET DISLOVE = $dislove WHERE TouiteID = $id;";
            $stmt2 = $db->prepare($sql2);
            $stmt2->execute();
            $love = $touite['LOVE'];
            $love--;
            $sql4 = "UPDATE Touite SET LOVE = $love WHERE TouiteID = $id;";
            $stmt4 = $db->prepare($sql4);
            $stmt4->execute();
            $sql5 = "UPDATE Opinion SET IsLove = 0, IsDislove = 1 WHERE UtilisateurID = $idUtil AND TouiteID = $id;";
            $stmt5 = $db->prepare($sql5);
            $stmt5->execute();
        } else if (($isLove == 0) && ($isDislove == 1)) {
            $dislove--;
            $sql2 = "UPDATE Touite SET DISLOVE = $dislove WHERE TouiteID = $id;";
            $stmt2 = $db->prepare($sql2);
            $stmt2->execute();
            $sql6 = "UPDATE Opinion SET IsDislove = 0 WHERE UtilisateurID = $idUtil AND TouiteID = $id;";
            $stmt6 = $db->prepare($sql6);
            $stmt6->execute();
        }
    }
}