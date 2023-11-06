<?php
declare(strict_types=1);
namespace Iutncy\Sae\Touites;
use Iutncy\Sae\User;

class Touite {
    private string $texte;
    private string $date;
    private User $auteur;
    private array $liTag;
}