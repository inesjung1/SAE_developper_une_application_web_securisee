<?php
declare(strict_types=1);
namespace Iutncy\Sae\Touites;
use Iutncy\Sae\User\User;
class Touite {
    private string $texte;
    private string $date;
    private User $auteur;
    private array $liTag;

    private int $love;

    private int $dislove;
    public function __construct(string $texte, string $date, User $auteur, array $liTag) {
        $this->texte = $texte;
        $this->date = $date;
        $this->auteur = $auteur;
        $this->liTag = $liTag;
        $this->love = 0;
        $this->dislove = 0;
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

    public function getLove(): int
    {
        return $this->love;
    }

    public function getDislove(): int
    {
        return $this->dislove;
    }

    public function addLove(): void
    {
        $this->love++;
    }

    public function addDislove(): void
    {
        $this->dislove++;
    }
}