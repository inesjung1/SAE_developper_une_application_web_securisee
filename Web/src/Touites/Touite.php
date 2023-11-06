<?php
declare(strict_types=1);
namespace Iutncy\Sae\Touites;
use Iutncy\Sae\User\User;
class Touite {
    private string $texte;
    private string $date;
    private User $auteur;
    private array $liTag;
    public function __construct(string $texte, string $date, User $auteur, array $liTag) {
        $this->texte = $texte;
        $this->date = $date;
        $this->auteur = $auteur;
        $this->liTag = $liTag;
    }
    public function getTexte(): string {
        return $this->texte;
    }
    public function getDate(): string {
        return $this->date;
    }
    public function getAuteur(): User {
        return $this->auteur;
    }
    public function getLiTag(): array {
        return $this->liTag;
    }
}