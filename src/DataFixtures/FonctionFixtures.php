<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use App\Entity\Employe;
use App\Entity\Fonction;



class FonctionFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 10; $i++) {
            $fonction = new Fonction();
            $fonction->setNoFonction("2422lmlm" . $i);
            $fonction->setLibFonction("Jean" . $i);
    
            $manager->persist($fonction);
        }
        $manager->flush();
    }
}
