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
        array_push($this->touites, $touite);
    }
}