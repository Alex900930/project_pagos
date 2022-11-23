<?php

namespace App\Entity;

use App\Repository\OtraInfoRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OtraInfoRepository::class)]
class OtraInfo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nombre = null;

    #[ORM\Column(length: 255)]
    private ?string $descripcion = null;

    #[ORM\Column(nullable: true)]
    private ?int $cantidad = null;

    #[ORM\Column(length: 3, nullable: true)]
    private ?string $codigo_moneda = null;

    #[ORM\Column(nullable: true)]
    private ?int $monto_pagar = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $return_url = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $cancel_url = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $notificacion_url = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $referencia = null;

    #[ORM\Column(nullable: true)]
    private ?int $expira_dias = null;

    #[ORM\Column(length: 2, nullable: true)]
    private ?string $lenguaje = null;

    #[ORM\Column(nullable: true)]
    private ?int $tipo_uso = null;

    #[ORM\Column]
    private ?int $reason_id = null;

    #[ORM\Column(length: 60)]
    private ?string $nameMetodo = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getCantidad(): ?int
    {
        return $this->cantidad;
    }

    public function setCantidad(?int $cantidad): self
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    public function getCodigoMoneda(): ?string
    {
        return $this->codigo_moneda;
    }

    public function setCodigoMoneda(?string $codigo_moneda): self
    {
        $this->codigo_moneda = $codigo_moneda;

        return $this;
    }

    public function getMontoPagar(): ?int
    {
        return $this->monto_pagar;
    }

    public function setMontoPagar(?int $monto_pagar): self
    {
        $this->monto_pagar = $monto_pagar;

        return $this;
    }

    public function getReturnUrl(): ?string
    {
        return $this->return_url;
    }

    public function setReturnUrl(?string $return_url): self
    {
        $this->return_url = $return_url;

        return $this;
    }

    public function getCancelUrl(): ?string
    {
        return $this->cancel_url;
    }

    public function setCancelUrl(?string $cancel_url): self
    {
        $this->cancel_url = $cancel_url;

        return $this;
    }

    public function getNotificacionUrl(): ?string
    {
        return $this->notificacion_url;
    }

    public function setNotificacionUrl(?string $notificacion_url): self
    {
        $this->notificacion_url = $notificacion_url;

        return $this;
    }

    public function getReferencia(): ?string
    {
        return $this->referencia;
    }

    public function setReferencia(?string $referencia): self
    {
        $this->referencia = $referencia;

        return $this;
    }

    public function getExpiraDias(): ?int
    {
        return $this->expira_dias;
    }

    public function setExpiraDias(?int $expira_dias): self
    {
        $this->expira_dias = $expira_dias;

        return $this;
    }

    public function getLenguaje(): ?string
    {
        return $this->lenguaje;
    }

    public function setLenguaje(?string $lenguaje): self
    {
        $this->lenguaje = $lenguaje;

        return $this;
    }

    public function getTipoUso(): ?int
    {
        return $this->tipo_uso;
    }

    public function setTipoUso(?int $tipo_uso): self
    {
        $this->tipo_uso = $tipo_uso;

        return $this;
    }

    public function getReasonId(): ?int
    {
        return $this->reason_id;
    }

    public function setReasonId(int $reason_id): self
    {
        $this->reason_id = $reason_id;

        return $this;
    }

    public function getNameMetodo(): ?string
    {
        return $this->nameMetodo;
    }

    public function setNameMetodo(string $nameMetodo): self
    {
        $this->nameMetodo = $nameMetodo;

        return $this;
    }
}
