<?php

namespace App\Entity;

use App\Repository\FonctionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FonctionRepository::class)
 */
class Fonction
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
    private $NoFonction;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $LibFonction;

    /**
     * @ORM\OneToMany(targetEntity=Employe::class, mappedBy="Fonction")
     */
    private $employes;

    public function __construct()
    {
        $this->employes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNoFonction(): ?string
    {
        return $this->NoFonction;
    }

    public function setNoFonction(string $NoFonction): self
    {
        $this->NoFonction = $NoFonction;

        return $this;
    }

    public function getLibFonction(): ?string
    {
        return $this->LibFonction;
    }

    public function setLibFonction(string $LibFonction): self
    {
        $this->LibFonction = $LibFonction;

        return $this;
    }

    /**
     * @return Collection<int, Employe>
     */
    public function getEmployes(): Collection
    {
        return $this->employes;
    }

    public function addEmploye(Employe $employe): self
    {
        if (!$this->employes->contains($employe)) {
            $this->employes[] = $employe;
            $employe->setFonction($this);
        }

        return $this;
    }

    public function removeEmploye(Employe $employe): self
    {
        if ($this->employes->removeElement($employe)) {
            // set the owning side to null (unless already changed)
            if ($employe->getFonction() === $this) {
                $employe->setFonction(null);
            }
        }

        return $this;
    }
}
