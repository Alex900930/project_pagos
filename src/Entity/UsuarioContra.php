<?php

namespace App\Entity;

use App\Repository\UsuarioContraRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UsuarioContraRepository::class)]
class UsuarioContra
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 60, nullable: true)]
    private ?string $usuario = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $contraseña = null;

    #[ORM\Column(length: 60, nullable: true)]
    private ?string $nombreMetodo = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsuario(): ?string
    {
        return $this->usuario;
    }

    public function setUsuario(?string $usuario): self
    {
        $this->usuario = $usuario;

        return $this;
    }

    public function getContraseña(): ?string
    {
        return $this->contraseña;
    }

    public function setContraseña(?string $contraseña): self
    {
        $this->contraseña = $contraseña;

        return $this;
    }

    public function getNombreMetodo(): ?string
    {
        return $this->nombreMetodo;
    }

    public function setNombreMetodo(?string $nombreMetodo): self
    {
        $this->nombreMetodo = $nombreMetodo;

        return $this;
    }
}
