<?php

namespace App\Entity;

use App\Repository\ReservationRdvRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReservationRdvRepository::class)
 */
class ReservationRdv
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="reservationRdvs")
     */
    private $idUser;

    /**
     * @ORM\OneToOne(targetEntity=RDV::class, cascade={"persist", "remove"})
     */
    private $idRDV;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdUser(): ?user
    {
        return $this->idUser;
    }

    public function setIdUser(?user $idUser): self
    {
        $this->idUser = $idUser;

        return $this;
    }

    public function getIdRDV(): ?RDV
    {
        return $this->idRDV;
    }

    public function setIdRDV(?RDV $idRDV): self
    {
        $this->idRDV = $idRDV;

        return $this;
    }
}
