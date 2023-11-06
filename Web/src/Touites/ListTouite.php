<?php

declare(strict_types=1);
namespace Iutncy\Sae\Touites;

class ListTouite
{
    private array $touites;

    public function __construct()
    {
        $this->touites = [];
    }

    public function addTouite(Touite $touite): void
    {
        //ajout d'un touite dans la liste par ordre chronologique
        $add = false;
        $i = 0;
        while ($i < count($this->touites) && !$add) {
            if ($touite->getDate() > $this->touites[$i]->getDate()) {
                array_splice($this->touites, $i, 0, [$touite]);
                $add = true;
            }
            $i++;
        }
    }
}