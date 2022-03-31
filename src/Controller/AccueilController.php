<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    /**
     * @Route("/accueil", name="accueil")
     */
    public function index(CategorieRepository $repository): Response
    {
     
        // lire les accueil
       $lesCategories = $repository->findAll();
       return $this->render('accueil/index.html.twig', [
          // 'controller_name' => 'CategorieController',
           'lesCategories' => $lesCategories,
        
       ]);

           
    
     
    }
}
