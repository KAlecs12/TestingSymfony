<?php

namespace App\Entity;

use App\Repository\HoraireRepository;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity(repositoryClass= HoraireRepository::class)
 */
class Horaire
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue]
     * @ORM\Column(type= "integer")
     */
    private $id;

    /**
     * @ORM\Column(type= "dateinterval")
     */
    private $Horaire;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHoraire(): ?\DateInterval
    {
        return $this->Horaire;
    }

    public function setHoraire(\DateInterval $Horaire): self
    {
        $this->Horaire = $Horaire;

        return $this;
    }
}
