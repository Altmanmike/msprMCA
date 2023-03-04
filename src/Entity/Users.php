<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
//use ApiPlatform\Metadata\ApiResource;
use App\Repository\UsersRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=UsersRepository::class)
 */
class Users
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $cryptedKey;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getCryptedKey(): ?string
    {
        return $this->cryptedKey;
    }

    public function setCryptedKey(string $cryptedKey): self
    {
        $this->cryptedKey = $cryptedKey;

        return $this;
    }
}
