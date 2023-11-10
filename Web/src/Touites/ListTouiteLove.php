<?php

namespace Iutncy\Sae\Touites;

class ListTouiteLove
{
    public function __construct()
    {
        $this->touitesLove = [];
    }

    public function addTouite(Touite $touite): void
    {
        //ajout d'un touite dans la liste par ordre chronologique
        $add = false;
        $i = 0;
        //récupère le nombre de like du touite en paramètre
        $db = ConnectionFactory::makeConnection();
        $id = $touite->getId();
        $sql = "SELECT LOVE FROM Touite WHERE TouiteID = $id;";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $like = $stmt->fetch();
        $nbLike = $like['LOVE'];
        while ($i < count($this->touitesLove) && !$add) {
            $t = $this->touitesLove[$i];
            $idT = $t->getId();
            $sql2 = "SELECT LOVE FROM Touite WHERE TouiteID = $idT;";
            $stmt2 = $db->prepare($sql2);
            $stmt2->execute();
            $like2 = $stmt2->fetch();
            $nbLike2 = $like2['LOVE'];
            if ($nbLike > $nbLike2) {
                array_splice($this->touitesLove, $i, 0, [$touite]);
                $add = true;
            } else
            $i++;
        }
    }

}