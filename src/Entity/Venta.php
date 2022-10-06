<?php

namespace App\Entity;

use App\Repository\VentaRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VentaRepository::class)]
class Venta
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $created = null;

    #[ORM\Column]
    private ?int $precio = null;

    #[ORM\Column(length: 255)]
    private ?string $experiencia = null;

    #[ORM\ManyToOne(targetEntity: Cliente::class, inversedBy: 'ventas')]
    #[ORM\JoinColumn('cliente_id', referencedColumnName: 'id')]
    #[ORM\JoinColumn('cliente_created_id', referencedColumnName: 'created')]
    private ?Cliente $cliente;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getPrecio(): ?int
    {
        return $this->precio;
    }

    public function setPrecio(int $precio): self
    {
        $this->precio = $precio;

        return $this;
    }

    public function getExperiencia(): ?string
    {
        return $this->experiencia;
    }

    public function setExperiencia(string $experiencia): self
    {
        $this->experiencia = $experiencia;

        return $this;
    }

    /**
     * @return Cliente|null
     */
    public function getCliente(): ?Cliente
    {
        return $this->cliente;
    }

    /**
     * @param Cliente|null $cliente
     * @return Venta
     */
    public function setCliente(?Cliente $cliente): Venta
    {
        $this->cliente = $cliente;
        return $this;
    }
}
