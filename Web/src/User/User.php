<?php
declare(strict_types=1);
namespace Iutncy\Sae\User;

use Iutncy\Sae\db\ConnectionFactory;
class User {
    private string $email;
    private string $pseudo;
    private string $password;

    private int $idU;
    public function __construct(string $email, string $pseudo, string $password) {
        $this->email = $email;
        $this->pseudo = $pseudo;
        $this->password = $password;
        $db = ConnectionFactory::makeConnection();
        $sql = "SELECT UtilisateurID FROM Utilisateur WHERE AdresseEmail = '$email'";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $this->idU = $stmt->fetch()['UtilisateurID'];
    }
    public function getTouites(): array {
        return array();
    }
    public function getEmail(): string {
        return $this->email;
    }
    public function getPseudo(): string {
        return $this->pseudo;
    }

    public function getId(): int
    {
        return $this->idU;
    }
}