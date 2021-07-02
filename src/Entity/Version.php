<?php

namespace App\Entity;

use App\Repository\VersionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=VersionRepository::class)
 */
class Version
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @Assert\Regex("/^\d+((\.\d+){0,1}\.\d+){0,1}$/")
     */
    private $numero;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $commentaires;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $urlMac;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $urlWin;

    /**
     * @ORM\Column(type="date")
     */
    private $dateAjout;

    /**
     * @ORM\ManyToOne(targetEntity=Logiciel::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $logiciel;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="versions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    public function __construct(){
        $this->dateAjout = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(string $numero): self
    {
        $this->numero = $numero;

        return $this;
    }

    public function getCommentaires(): ?string
    {
        return $this->commentaires;
    }

    public function setCommentaires(?string $commentaires): self
    {
        $this->commentaires = $commentaires;

        return $this;
    }

    public function getUrlMac(): ?string
    {
        return $this->urlMac;
    }

    public function setUrlMac(?string $urlMac): self
    {
        $this->urlMac = $urlMac;

        return $this;
    }

    public function getUrlWin(): ?string
    {
        return $this->urlWin;
    }

    public function setUrlWin(?string $urlWin): self
    {
        $this->urlWin = $urlWin;

        return $this;
    }

    public function getDateAjout(): ?\DateTimeInterface
    {
        return $this->dateAjout;
    }

    public function setDateAjout(\DateTimeInterface $dateAjout): self
    {
        $this->dateAjout = $dateAjout;

        return $this;
    }

    public function getLogiciel(): ?Logiciel
    {
        return $this->logiciel;
    }

    public function setLogiciel(?Logiciel $logiciel): self
    {
        $this->logiciel = $logiciel;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }
}
