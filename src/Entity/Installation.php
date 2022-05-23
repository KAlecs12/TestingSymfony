<?php

namespace App\Entity;

use App\Repository\InstallationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass= InstallationRepository::class)
 */
class Installation
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
    private $Nom;

    /**
     * @ORM\Column(type= "integer")
     */
    private $Nb_max_personnes;

    /**
     * @ORM\Column(type= "dateinterval")
     */
    private $Duree_utilisation_max;

    /**
     * @ORM\OneToMany(mappedBy= "id_installation", targetEntity= Rdv::class)
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

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): self
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getNbMaxPersonnes(): ?int
    {
        return $this->Nb_max_personnes;
    }

    public function setNbMaxPersonnes(int $Nb_max_personnes): self
    {
        $this->Nb_max_personnes = $Nb_max_personnes;

        return $this;
    }

    public function getDureeUtilisationMax(): ?\DateInterval
    {
        return $this->Duree_utilisation_max;
    }

    public function setDureeUtilisationMax(\DateInterval $Duree_utilisation_max): self
    {
        $this->Duree_utilisation_max = $Duree_utilisation_max;

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
            $rdv->setIdInstallation($this);
        }

        return $this;
    }

    public function removeRdv(Rdv $rdv): self
    {
        if ($this->rdvs->removeElement($rdv)) {
            // set the owning side to null (unless already changed)
            if ($rdv->getIdInstallation() === $this) {
                $rdv->setIdInstallation(null);
            }
        }

        return $this;
    }
}
