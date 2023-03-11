<?php

namespace App\Entity;

use App\Repository\CryptedKeysRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CryptedKeysRepository::class)]
class CryptedKeys
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Nom = null;

    #[ORM\Column(length: 255)]
    private ?string $Cle = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): self
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getCle(): ?string
    {
        return $this->Cle;
    }

    public function setCle(string $Cle): self
    {
        $this->Cle = $Cle;

        return $this;
    }
}
