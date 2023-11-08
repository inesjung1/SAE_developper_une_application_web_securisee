<?php

namespace Iutncy\Sae\Action;

use Iutncy\Sae\db\ConnectionFactory;

class LoveAction
{
    public function __construct()
    {
    }

    public function execute(): string{
        $db = ConnectionFactory::makeConnection();
        $idUtil = $_GET['idU'];
        $idTouite = $_GET['id'];
        $sql0 = "SELECT COUNT(*) FROM Opinion WHERE UtilisateurID = $idUtil AND TouiteID = $idTouite;";
        $stmt0 = $db->prepare($sql0);
        $stmt0->execute();
        $opinion = $stmt0->fetch();
        $count = $opinion['COUNT(*)'];
        if($count == 0) {
            $sql = "INSERT INTO Opinion (UtilisateurID, TouiteID, IsLove, IsDislove) VALUES ($idUtil, $idTouite, 1, 0);";
            $stmt = $db->prepare($sql);
            $stmt->execute();
        }
        $this->ajouterLikeTouite();
        $action = base64_decode($_GET['aaction']);
        header('Location: '.$_SERVER['PHP_SELF'].'?'.$action);
        return '';
    }

    public function ajouterLikeTouite(){
        $idUtil = $_GET['idU'];
        $db = ConnectionFactory::makeConnection();
        $sql0 = "SELECT * FROM Opinion WHERE UtilisateurID = $idUtil;";
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
        $love = $touite['LOVE'];

        if(($isLove == 0)&&($isDislove == 0)){
            $love++;
            $sql2 = "UPDATE Touite SET LOVE = $love WHERE TouiteID = $id;";
            $stmt2 = $db->prepare($sql2);
            $stmt2->execute();
            $sql3 = "UPDATE Opinion SET IsLove = 1 WHERE UtilisateurID = $idUtil;";
            $stmt3 = $db->prepare($sql3);
            $stmt3->execute();
        }else if(($isLove == 0)&&($isDislove == 1)) {
            $love++;
            $sql2 = "UPDATE Touite SET LOVE = $love WHERE TouiteID = $id;";
            $stmt2 = $db->prepare($sql2);
            $stmt2->execute();
            $dislove = $touite['DISLOVE'];
            $dislove--;
            $sql4 = "UPDATE Touite SET DISLOVE = $dislove WHERE TouiteID = $id;";
            $stmt4 = $db->prepare($sql4);
            $stmt4->execute();
            $sql5 = "UPDATE Opinion SET IsLove = 1, IsDislove = 0 WHERE UtilisateurID = $idUtil;";
            $stmt5 = $db->prepare($sql5);
            $stmt5->execute();
        }else if(($isLove == 1)&&($isDislove == 0)) {
            $love--;
            $sql2 = "UPDATE Touite SET LOVE = $love WHERE TouiteID = $id;";
            $stmt2 = $db->prepare($sql2);
            $stmt2->execute();
            $sql6 = "UPDATE Opinion SET IsLove = 0 WHERE UtilisateurID = $idUtil;";
            $stmt6 = $db->prepare($sql6);
            $stmt6->execute();
        }
    }

}