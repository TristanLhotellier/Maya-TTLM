<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CategorieRepository::class)
 */
class Categorie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank
     * @Assert\Length(
     *     min = 3,
     *     max = 50,
     *     minMessage = "Ce libellé est trop court",
     *     maxMessage = "Ce libellé est trop long"
     * )
     */
    private $libelle;


 
    /**
     * @ORM\OneToMany(targetEntity=Produit::class, mappedBy="categorie")
     */
    private $produits;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $couleur;

    public function __construct()
    {
        $this->produits = new ArrayCollection();
    }

    public function __toString(){
        return $this->getLibelle();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

   
    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

   public function getChoixCouleur(): ?string 
   {
       $choixCouleur='';
    if($this->couleur != null ){
       if($this->couleur == 'rouge'){
           $choixCouleur='card bg-danger';
       }
       elseif($this->couleur == 'gris'){
           $choixCouleur='card bg-secondary';
       }
       elseif($this->couleur == 'bleu'){
        $choixCouleur='card bg-primary';
        }
        elseif($this->couleur == 'vert'){
            $choixCouleur='card bg-success';
        }
        elseif($this->couleur == 'jaune'){
            $choixCouleur='card bg-warning';
        }
        elseif($this->couleur == 'blanc'){
            $choixCouleur='card bg-light';
        }
        return $choixCouleur;
        }
    }

   

    /**
     * @return Collection<int, Produit>
     */
    public function getProduits(): Collection
    {
        return $this->produits;
    }

    public function addProduit(Produit $produit): self
    {
        if (!$this->produits->contains($produit)) {
            $this->produits[] = $produit;
            $produit->setCategorie($this);
        }

        return $this;
    }

    public function removeProduit(Produit $produit): self
    {
        if ($this->produits->removeElement($produit)) {
            // set the owning side to null (unless already changed)
            if ($produit->getCategorie() === $this) {
                $produit->setCategorie(null);
            }
        }

        return $this;
    }

    public function getCouleur(): ?string
    {
        return $this->couleur;
    }

    public function setCouleur(string $couleur): self
    {
        $this->couleur = $couleur;

        return $this;
    }
}
