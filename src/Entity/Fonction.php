<?php

namespace App\Entity;

use App\Repository\FonctionRepository;
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
     * @ORM\Column(type="string", length=50)
     */
    private $libFonction;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibFonction(): ?string
    {
        return $this->libFonction;
    }

    public function setLibFonction(string $libFonction): self
    {
        $this->libFonction = $libFonction;

        return $this;
    }
}
