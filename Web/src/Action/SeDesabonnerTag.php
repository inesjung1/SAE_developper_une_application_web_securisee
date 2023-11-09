<?php

namespace Iutncy\Sae\Action;

use Iutncy\Sae\Db\ConnectionFactory;

class SeDesabonnerTag extends Action
{
    public function __construct()
    {
    }

    public function execute(): string{
        $db = ConnectionFactory::makeConnection();
        $idTouite = $_GET['id'];
        $sql0 = "SELECT TagID FROM contienttag WHERE TouiteID = $idTouite";
        $stmt0 = $db->prepare($sql0);
        $stmt0->execute();
        $touite = $stmt0->fetch();
        $tagID = $touite['TagID'];
        $monId = $_GET['idU'];
        $sql = "DELETE FROM abonnementtag WHERE AbonneUtilisateurID = $monId AND TagID = $tagID;";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $action = base64_decode($_GET['aaction']);
        header('Location: '.$_SERVER['PHP_SELF'].'?'.$action);
        return '';
    }

}