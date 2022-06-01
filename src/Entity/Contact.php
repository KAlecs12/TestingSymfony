<?php

namespace App\Entity;

use App\Repository\ContactRepository;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity(repositoryClass= ContactRepository::class)
 */
class Contact
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type= "integer")
     */
    private $id_contact;

    /** 
     * @ORM\Column(type= "string", length= 45, nullable= true)
     */
     private $sujet;

     /**
      * @ORM\Column(type="text")
      */
    private $message;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="contacts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idUser;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="contactspro")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idPro;

    /**
     * @ORM\Column(type="boolean")
     */
    private $deleted;

    /**
     * @ORM\Column(type="string", length=255, nullable= true)
     */
    private $approuved;

    public function getId(): ?int
    {
        return $this->id_contact;
    }

    public function getSujet(): ?string
    {
        return $this->sujet;
    }

    public function setSujet(?string $sujet): self
    {
        $this->sujet = $sujet;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getIdUser(): ?User
    {
        return $this->idUser;
    }

    public function setIdUser(?User $idUser): self
    {
        $this->idUser = $idUser;

        return $this;
    }

    public function getIdPro(): ?User
    {
        return $this->idPro;
    }

    public function setIdPro(?User $idPro): self
    {
        $this->idPro = $idPro;

        return $this;
    }

    public function getDeleted(): ?bool
    {
        return $this->deleted;
    }

    public function setDeleted(bool $deleted): self
    {
        $this->deleted = $deleted;

        return $this;
    }

    public function getApprouved(): ?string
    {
        return $this->approuved;
    }

    public function setApprouved(string $approuved): self
    {
        $this->approuved = $approuved;

        return $this;
    }
}
