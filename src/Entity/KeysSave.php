<?php

namespace App\Entity;

use App\Repository\KeysSaveRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: KeysSaveRepository::class)]
class KeysSave
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 60)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $apiKey1 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $apiKey2 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $apiKey3 = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getApiKey1(): ?string
    {
        return $this->apiKey1;
    }

    public function setApiKey1(string $apiKey1): self
    {
        $this->apiKey1 = $apiKey1;

        return $this;
    }

    public function getApiKey2(): ?string
    {
        return $this->apiKey2;
    }

    public function setApiKey2(?string $apiKey2): self
    {
        $this->apiKey2 = $apiKey2;

        return $this;
    }

    public function getApiKey3(): ?string
    {
        return $this->apiKey3;
    }

    public function setApiKey3(?string $apiKey3): self
    {
        $this->apiKey3 = $apiKey3;

        return $this;
    }
}
