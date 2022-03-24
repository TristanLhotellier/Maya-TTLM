<?php

namespace App\Entity;

use App\Repository\EmployeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=EmployeRepository::class)
 */
class Employe
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Length(
     *     min = 3,
     *     max = 50,
     *     minMessage = "Ce libellé est trop court",
     *     maxMessage = "Ce libellé est trop long"
     * )
     */
    private $Matricule;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $Nom;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $Prenom;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $Rue;

    /**
     * @ORM\Column(type="integer")
     */
    private $CodePostal;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $Ville;

    /**
     * @ORM\Column(type="datetime")
     */
    private $DateEmbauche;

    /**
     * @ORM\Column(type="integer")
     */
    private $Salaire;

    /**
     * @ORM\ManyToOne(targetEntity=Fonction::class, inversedBy="employes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Fonction;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMatricule(): ?string
    {
        return $this->Matricule;
    }

    public function setMatricule(string $Matricule): self
    {
        $this->Matricule = $Matricule;

        return $this;
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

    public function getPrenom(): ?string
    {
        return $this->Prenom;
    }

    public function setPrenom(string $Prenom): self
    {
        $this->Prenom = $Prenom;

        return $this;
    }

    public function getRue(): ?string
    {
        return $this->Rue;
    }

    public function setRue(string $Rue): self
    {
        $this->Rue = $Rue;

        return $this;
    }

    public function getCodePostal(): ?int
    {
        return $this->CodePostal;
    }

    public function setCodePostal(int $CodePostal): self
    {
        $this->CodePostal = $CodePostal;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->Ville;
    }

    public function setVille(string $Ville): self
    {
        $this->Ville = $Ville;

        return $this;
    }

    public function getDateEmbauche(): ?\DateTimeInterface
    {
        return $this->DateEmbauche;
    }

    public function setDateEmbauche(\DateTimeInterface $DateEmbauche): self
    {
        $this->DateEmbauche = $DateEmbauche;

        return $this;
    }

    public function getSalaire(): ?int
    {
        return $this->Salaire;
    }

    public function setSalaire(int $Salaire): self
    {
        $this->Salaire = $Salaire;

        return $this;
    }

    public function getFonction(): ?Fonction
    {
        return $this->Fonction;
    }

    public function setFonction(?Fonction $Fonction): self
    {
        $this->Fonction = $Fonction;

        return $this;
    }
}
