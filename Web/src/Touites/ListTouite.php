<?php

declare(strict_types=1);
namespace Iutnc\Sae;

class ListTouite
{
    private array $touites;

    public function __construct()
    {
        $this->touites = [];
    }

    public function addTouite(Touite $touite): void
    {
        $this->touites[] = $touite;
    }
}