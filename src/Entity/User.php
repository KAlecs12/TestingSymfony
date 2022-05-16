<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;
    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $firstName;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;
    private $plainPassword;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $imageFilename;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $facturation;

    /**
     * @ORM\ManyToMany(targetEntity=Stage::class, inversedBy="users")
     */
    private $id_stage;

    /**
     * @ORM\ManyToOne(targetEntity=Recapitulatif::class, inversedBy="users")
     */
    private $idRecapitulatif;

    /**
     * @ORM\OneToMany(targetEntity=ReservationRdv::class, mappedBy="idUser")
     */
    private $reservationRdvs;

    /**
     * @ORM\OneToMany(targetEntity=Contact::class, mappedBy="idUser")
     */
    private $contacts;

    /**
     * @ORM\OneToMany(targetEntity=Facture::class, mappedBy="idUser")
     */
    private $factures;

    public function __construct()
    {
        $this->id_stage = new ArrayCollection();
        $this->reservationRdvs = new ArrayCollection();
        $this->contacts = new ArrayCollection();
        $this->factures = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getEmail(): ?string
    {
        return $this->email;
    }
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }
    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }
    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }
    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }
    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }
    /**
     * This method can be removed in Symfony 6.0 - is not needed for apps that do not check user passwords.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }
    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        $this->plainPassword = null;
    }
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }
    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }
    public function setPlainPassword(string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    public function getImageFilename(): ?string
    {
        return $this->imageFilename;
    }

    public function setImageFilename(?string $imageFilename): self
    {
        $this->imageFilename = $imageFilename;

        return $this;
    }

    public function getFacturation(): ?string
    {
        return $this->facturation;
    }

    public function setFacturation(?string $facturation): self
    {
        $this->facturation = $facturation;

        return $this;
    }

    /**
     * @return Collection<int, stage>
     */
    public function getIdStage(): Collection
    {
        return $this->id_stage;
    }

    public function addIdStage(stage $idStage): self
    {
        if (!$this->id_stage->contains($idStage)) {
            $this->id_stage[] = $idStage;
        }

        return $this;
    }

    public function removeIdStage(stage $idStage): self
    {
        $this->id_stage->removeElement($idStage);

        return $this;
    }

    public function getIdRecapitulatif(): ?Recapitulatif
    {
        return $this->idRecapitulatif;
    }

    public function setIdRecapitulatif(?Recapitulatif $idRecapitulatif): self
    {
        $this->idRecapitulatif = $idRecapitulatif;

        return $this;
    }

    /**
     * @return Collection<int, ReservationRdv>
     */
    public function getReservationRdvs(): Collection
    {
        return $this->reservationRdvs;
    }

    public function addReservationRdv(ReservationRdv $reservationRdv): self
    {
        if (!$this->reservationRdvs->contains($reservationRdv)) {
            $this->reservationRdvs[] = $reservationRdv;
            $reservationRdv->setIdUser($this);
        }

        return $this;
    }

    public function removeReservationRdv(ReservationRdv $reservationRdv): self
    {
        if ($this->reservationRdvs->removeElement($reservationRdv)) {
            // set the owning side to null (unless already changed)
            if ($reservationRdv->getIdUser() === $this) {
                $reservationRdv->setIdUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Contact>
     */
    public function getContacts(): Collection
    {
        return $this->contacts;
    }

    public function addContact(Contact $contact): self
    {
        if (!$this->contacts->contains($contact)) {
            $this->contacts[] = $contact;
            $contact->setIdUser($this);
        }

        return $this;
    }

    public function removeContact(Contact $contact): self
    {
        if ($this->contacts->removeElement($contact)) {
            // set the owning side to null (unless already changed)
            if ($contact->getIdUser() === $this) {
                $contact->setIdUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Facture>
     */
    public function getFactures(): Collection
    {
        return $this->factures;
    }

    public function addFacture(Facture $facture): self
    {
        if (!$this->factures->contains($facture)) {
            $this->factures[] = $facture;
            $facture->setIdUser($this);
        }

        return $this;
    }

    public function removeFacture(Facture $facture): self
    {
        if ($this->factures->removeElement($facture)) {
            // set the owning side to null (unless already changed)
            if ($facture->getIdUser() === $this) {
                $facture->setIdUser(null);
            }
        }

        return $this;
    }
}
