<?php

namespace App\Entity;

use App\Repository\ChevalRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ChevalRepository::class)
 */
class Cheval
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
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $box;

    /**
     *@ORM\ManyToOne(targetEntity=User::class, inversedBy="cheval")
     */
    private $idUser;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getBox(): ?string
    {
        return $this->box;
    }

    public function setBox(string $box): self
    {
        $this->box = $box;

        return $this;
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
}
