<?php
namespace App\Models;

class User {
    protected int $id;
    protected string $username;
    protected string $email;
    protected string $role;
    protected ?string $passwordHash;
    protected ?string $avatarPath;
    protected ?string $displayName;

    public function __construct(
        int $id,
        string $username,
        string $email,
        string $role = 'user',
        ?string $passwordHash = null,
        ?string $avatarPath = null,
        ?string $displayName = null
    ) {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->role = $role;
        $this->passwordHash = $passwordHash;
        $this->avatarPath = $avatarPath;
        $this->displayName = $displayName;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getUsername(): string {
        return $this->username;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function getRole(): string {
        return $this->role;
    }

    public function getPasswordHash(): ?string {
        return $this->passwordHash;
    }

    public function getAvatarPath(): ?string {
        return $this->avatarPath;
    }

    public function getDisplayName(): ?string {
        return $this->displayName;
    }


    public function verifyPassword(string $password): bool {
        if ($this->passwordHash === null) {
            return false;
        }
        return password_verify($password, $this->passwordHash);
    }

   
    public static function hashPassword(string $password): string {
        return password_hash($password, PASSWORD_DEFAULT);
    }
}
