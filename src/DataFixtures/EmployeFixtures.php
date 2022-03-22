<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Employe;
use App\Entity\Fonction;

class EmployeFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // créer 10 utilisateurs
        for ($i = 0; $i < 10; $i++) {
            $user = new EmployeFixtures();
            $user->setFonction('4mp' . $i);
            $user->setMatricule('prout' . $i);
            $user->setNom('Dupont' . $i);
            $user->setPrenom('ponpon' . $i);
            $user->setRue('jeanhiver' . $i);
            $user->setCodePostal('56856' . $i);
            $user->setVille(sprintf('userdemo%d@exemple.com', $i));
            $user->setDateEmbauche('2019-01-01', $i);
            $user->setSalaire('20000' . $i);
          
            $manager->persist($user);
        }


        $manager->flush();
    }
}
