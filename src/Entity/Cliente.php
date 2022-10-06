<?php

namespace App\Entity;

use App\Repository\ClienteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: ClienteRepository::class)]
class Cliente
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid')]
    private ?Uuid $id = null;

    #[ORM\Column(type: 'string')]
    private string $name;

    #[ORM\Column(type:'datetime')]
    #[ORM\Id]
    private \DateTime $created;

    #[ORM\OneToMany(mappedBy: 'cliente', targetEntity: Venta::class, cascade: ["ALL"])]
    private array|Collection $ventas;

    public function __construct()
    {
        $this->ventas = new ArrayCollection();
    }

    public function __toString(): string
    {
        return sprintf("%s", $this->name);
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    /**
     * @param Uuid|null $id
     * @return Cliente
     */
    public function setId(?Uuid $id): Cliente
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Cliente
     */
    public function setName(string $name): Cliente
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreated(): \DateTime
    {
        return $this->created;
    }

    /**
     * @param \DateTime $created
     * @return Cliente
     */
    public function setCreated(\DateTime $created): Cliente
    {
        $this->created = $created;
        return $this;
    }

    /**
     * @return array|ArrayCollection|Collection
     */
    public function getVentas(): ArrayCollection|Collection|array
    {
        return $this->ventas;
    }

    /**
     * @param array|ArrayCollection|Collection $ventas
     * @return Cliente
     */
    public function setVentas(ArrayCollection|Collection|array $ventas): Cliente
    {
        $this->ventas = $ventas;
        return $this;
    }

    public function addVentas(Venta $venta) {
        if(!$this->ventas->contains($venta)){
            $venta->setCliente($this);
            $this->ventas->add($venta);
        }
        return $this;
    }

    public function removeVentas(Venta $venta) {
        if($this->ventas->contains($venta)) {
            $this->ventas->removeElement($venta);
        }
        return $this;
    }
}
