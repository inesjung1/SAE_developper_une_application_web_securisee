<?php
declare(strict_types=1);
namespace Iutncy\Sae\User;
class User {
    private string $email;
    private string $pseudo;
    private string $password;
    public function getTouites(): array {
        return array();
    }
    public function getEmail(): string {
        return $this->email;
    }
    public function getPseudo(): string {
        return $this->pseudo;
    }
}