<?php
declare(strict_types=1);
namespace Iutncy\Sae\Action;
use Iutncy\Sae\Db\ConnectionFactory;

class DeleteTouite extends Action
{

    public function __construct()
    {
    }

    public function execute(): string
    {
        if (!isset($_COOKIE['user'])) {
            setcookie('user', "0", time() + 3600, '/');
        }
        $db = ConnectionFactory::makeConnection();
        $idTouite = $_GET['idTouite'];

        $sql0 = "delete from Note where TouiteID = $idTouite;";
        $stmt0 = $db->prepare($sql0);
        $stmt0->execute();

        $sql0 = "delete from ListeTouiteUtilisateur where TouiteID = $idTouite;";
        $stmt0 = $db->prepare($sql0);
        $stmt0->execute();

        $sql0 = "delete from ListeTouiteAbonne where TouiteID = $idTouite;";
        $stmt0 = $db->prepare($sql0);
        $stmt0->execute();

        $sql0 = "delete from ContientTag where TouiteID = $idTouite;";
        $stmt0 = $db->prepare($sql0);
        $stmt0->execute();

        $sql0 = "delete from Opinion where TouiteID = $idTouite;";
        $stmt0 = $db->prepare($sql0);
        $stmt0->execute();

        $sql0 = "delete from Touite where TouiteID = $idTouite;";
        $stmt0 = $db->prepare($sql0);
        $stmt0->execute();

        $action = base64_decode($_GET['aaction']);
        header('Location: '.$_SERVER['PHP_SELF'].'?'.$action);
        return '';
    }
}