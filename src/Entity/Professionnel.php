<?php

namespace App\Entity;

use App\Repository\ProfessionnelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass= ProfessionnelRepository::class)
 */
 class Professionnel
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type= "integer")
     */
    private $id;

    /** 
     * @ORM\Column(type= "string", length= 255)
     */
    private $Identite;
    /**
     * @ORM\Column(type= "dateinterval")
     */
    private $Duree_moyenne;

    /**
     * @ORM\OneToMany(mappedBy= "id_professionnel", targetEntity= Rdv::class)
     */
    private $rdvs;

    public function __construct()
    {
        $this->rdvs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdentite(): ?string
    {
        return $this->Identite;
    }

    public function setIdentite(string $Identite): self
    {
        $this->Identite = $Identite;

        return $this;
    }

    public function getDureeMoyenne(): ?\DateInterval
    {
        return $this->Duree_moyenne;
    }

    public function setDureeMoyenne(\DateInterval $Duree_moyenne): self
    {
        $this->Duree_moyenne = $Duree_moyenne;

        return $this;
    }

    /**
     * @return Collection<int, Rdv>
     */
    public function getRdvs(): Collection
    {
        return $this->rdvs;
    }

    public function addRdv(Rdv $rdv): self
    {
        if (!$this->rdvs->contains($rdv)) {
            $this->rdvs[] = $rdv;
            $rdv->setIdProfessionnel($this);
        }

        return $this;
    }

    public function removeRdv(Rdv $rdv): self
    {
        if ($this->rdvs->removeElement($rdv)) {
            // set the owning side to null (unless already changed)
            if ($rdv->getIdProfessionnel() === $this) {
                $rdv->setIdProfessionnel(null);
            }
        }

        return $this;
    }
}
