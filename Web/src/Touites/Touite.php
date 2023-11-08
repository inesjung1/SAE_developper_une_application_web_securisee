<?php
declare(strict_types=1);
namespace Iutncy\Sae\Touites;
use Iutncy\Sae\User\User;
use Iutncy\Sae\db\ConnectionFactory;
class Touite {

    private int $id;
    private string $texte;
    private string $date;
    private User $auteur;
    private array $liTag;

    public function __construct(string $texte, string $date, User $auteur, array $liTag) {
        $db = ConnectionFactory::makeConnection();
        $sql = "SELECT TouiteID FROM Touite WHERE Texte = '$texte'" AND "DatePublication = '$date'";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $this->id = $stmt->fetch()['TouiteID'];
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


    public function getId(): int
    {
        return $this->id;
    }

}