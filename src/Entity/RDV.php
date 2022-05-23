<?php

namespace App\Entity;

use App\Repository\RDVRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass= RDVRepository::class)
 */
class RDV
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type= "integer")
     */
    private $id;

    /**
     * @ORM\Column(type= "datetime")
     */
    private $date;

    /**
     * @ORM\Column(type= "string", length= 45)
     */
    private $type;

    /**
     * @ORM\Column(type= "float")
     */
    private $tarification;

    /**
     * @ORM\ManyToOne(targetEntity= Installation::class, inversedBy= "rdvs")
     */
    private $id_installation;

    /**
     * ORM\ManyToOne(targetEntity= Professionnel::class, inversedBy= "rdvs")
     */
    private $id_professionnel;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getTarification(): ?float
    {
        return $this->tarification;
    }

    public function setTarification(float $tarification): self
    {
        $this->tarification = $tarification;

        return $this;
    }

    public function getIdInstallation(): ?Installation
    {
        return $this->id_installation;
    }

    public function setIdInstallation(?Installation $id_installation): self
    {
        $this->id_installation = $id_installation;

        return $this;
    }

    public function getIdProfessionnel(): ?Professionnel
    {
        return $this->id_professionnel;
    }

    public function setIdProfessionnel(?Professionnel $id_professionnel): self
    {
        $this->id_professionnel = $id_professionnel;

        return $this;
    }
}
