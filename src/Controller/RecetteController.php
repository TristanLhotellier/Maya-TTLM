<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Produit;
use App\Entity\Recette;
use App\Form\RecetteType;
use Doctrine\ORM\EntityManagerInterface;


class RecetteController extends AbstractController
{
    /**
     * @Route("/recette", name="app_recette")
     */
    public function index(): Response
    {
        // créer l'objet et le formulaire de création
        $recette = new Recette();
        $formCreation = $this->createForm(RecetteType::class, $recette);

        return $this->render('recette/index.html.twig', [
            'formCreation' => $formCreation->createView(),
            'lesRecettes' => $lesRecettes,

            // 'controller_name' => 'RecetteController',
        ]);
    }

    /**
     * @Route("/recette/creer", name="recette_creer")
     */
    public function creerRecette(EntityManagerInterface $entityManager): Response
    {
        // : Response        type de retour de la méthode creerRecette
        // pour récupérer le EntityManager
        //     on peut ajouter l'argument à la méthode comme ici  creerRecette(EntityManagerInterface $entityManager)
        //     ou on peut récupérer le EntityManager via $this->getDoctrine() comme ci-dessus en commentaire
        //        $entityManager = $this->getDoctrine()->getManager();

        // créer l'objet Recette
        $recette = new Recette();
        $recette->setNom('ratatouille');

        // chercher l'id du produit 'aubergine' et l'ajouter à la collection de produits de la recette
        $produit = $this->getDoctrine()
            ->getRepository(Produit::class)
            ->findOneBy(['libelle' => 'aubergine']);
        $recette->addProduit($produit);

        // chercher l'id du produit 'courgette' et l'ajouter à la collection de produits de la recette
        $produit = $this->getDoctrine()
            ->getRepository(Produit::class)
            ->findOneBy(['libelle' => 'courgettes']);
        $recette->addProduit($produit);

        // dire à Doctrine que l'objet sera (éventuellement) persisté
        $entityManager->persist($recette);

        // exécuter les requêtes (indiquées avec persist) ici il s'agit d'ordres INSERT qui seront exécutés
        $entityManager->flush();

        return new Response('Nouvelle recette enregistrée avec 2 produits, son id est : '.$recette->getId());
    }



}
