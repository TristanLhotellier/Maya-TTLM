<?php

namespace App\Controller;

use App\Entity\Fonction;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class FonctionController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }
    
    /**
     * @Route("/fonction/controler", name="app_fonction")
     */
    public function index(): Response
    {
           // lire les fonctions
           $lesFonctions = $this->entityManager->getRepository(Fonction::class)->findAllGreaterThanPrice();
            return $this->render('fonction/index.html.twig', [
                'lesFonctions' => $lesFonctions,
            ]);
    
    }
}
