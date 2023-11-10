<?php

namespace Iutncy\Sae\Action;

use Iutncy\Sae\Db\ConnectionFactory;

class SabonnerTag extends Action
{
    public function __construct()
    {
    }

    public function execute(): string{
        if (!isset($_COOKIE['user'])) {
            setcookie('user', "0", time() + 3600, '/');
        }
        $db = ConnectionFactory::makeConnection();
        $idTouite = $_GET['id'];
        $sql0 = "SELECT TagID FROM contienttag WHERE TouiteID = $idTouite";
        $stmt0 = $db->prepare($sql0);
        $stmt0->execute();
        $touite = $stmt0->fetch();
        $tagID = $touite['TagID'];
        $monId = $_GET['idU'];
        $sql = "INSERT INTO abonnementtag (AbonneUtilisateurID, TagID) VALUES ($monId, $tagID);";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $action = base64_decode($_GET['aaction']);
        header('Location: '.$_SERVER['PHP_SELF'].'?'.$action);
        return '';
    }

}