<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\Produit;
use App\Entity\Recette;
use App\Form\RecetteType;
use App\Repository\RecetteRepository;
use Doctrine\ORM\EntityManagerInterface;

class RecetteController extends AbstractController
{
    /**
     * @Route("/recette", name="app_recette")
     * @Route("/recette/demandermodification/{id<\d+>}", name="recette_demandermodification")
     */
    public function index($id = null, RecetteRepository $repository, Request $request): Response
    {
        // créer l'objet et le formulaire de création
        $recette = new Recette();
        $formCreation = $this->createForm(RecetteType::class, $recette);

        // si 2e route alors $id est renseigné et on  crée le formulaire de modification
        $formModificationView = null;
        if ($id != null) {
            // sécurité supplémentaire, on vérifie le token
            if ($this->isCsrfTokenValid('action-item'.$id, $request->get('_token'))) {
                $recetteModif = $repository->find($id);   // la recette à modifier
                $formModificationView = $this->createForm(RecetteType::class, $recetteModif)->createView();
            }
        }

        // lire les recettes
        $lesRecettes = $repository->findAll();
        return $this->render('recette/index.html.twig', [
            'formCreation' => $formCreation->createView(),
            'lesRecettes' => $lesRecettes,
            'formModification' => $formModificationView,
            'idRecetteModif' => $id,
        ]);
    }

    /**
 * @Route("/recette/ajouter", name="recette_ajouter")
 */
public function ajouter(Recette $recette = null, Request $request, EntityManagerInterface $entityManager, RecetteRepository $repository)
{
    //  $recette objet de la classe Recette, il contiendra les valeurs saisies dans les champs après soumission du formulaire.
    //  $request  objet avec les informations de la requête HTTP (GET, POST, ...)
    //  $entityManager  pour la persistance des données

    // création d'un formulaire de type RecetteType
    $recette = new Recette();
    $form = $this->createForm(RecetteType::class, $recette);

    // handleRequest met à jour le formulaire
    //  si le formulaire a été soumis, handleRequest renseigne les propriétés
    //      avec les données saisies par l'utilisateur et retournées par la soumission du formulaire
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // c'est le cas du retour du formulaire
        //         l'objet $recette a été automatiquement "hydraté" par Doctrine
        // dire à Doctrine que l'objet sera (éventuellement) persisté
        $entityManager->persist($recette);
        // exécuter les requêtes (indiquées avec persist) ici il s'agit de l'ordre INSERT qui sera exécuté
        $entityManager->flush();
        // ajouter un message flash de succès pour informer l'utilisateur
        $this->addFlash(
            'success',
            'La recette ' . $recette->getNom() . ' a été ajoutée.'
        );
        // rediriger vers l'affichage des recettes qui comprend le formulaire pour l"ajout d'une nouvelle recette
        return $this->redirectToRoute('app_recette');

    } else {
// affichage de la liste des recettes avec le formulaire de création et ses erreurs
        // lire les recettes
        $lesRecettes = $repository->findAll();
        // rendre la vue
        return $this->render('recette/index.html.twig', [
            'formCreation' => $form->createView(),
            'lesRecettes' => $lesRecettes,
            'formModification' => null,
            'idRecetteModif' => null,
        ]);
    }
}

/**
 * @Route("/recette/modifier/{id<\d+>}", name="recette_modifier")
 */
public function modifier(Recette $recette = null, $id = null, Request $request, EntityManagerInterface $entityManager, RecetteRepository $repository)
{
    //  Symfony 4 est capable de retrouver la recette à l'aide de Doctrine ORM directement en utilisant l'id passé dans la route
    $form = $this->createForm(RecetteType::class, $recette);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        // va effectuer la requête d'UPDATE en base de données
        // pas besoin de "persister" l'entité car l'objet a déjà été retrouvé à partir de Doctrine ORM.
        $entityManager->flush();
        $this->addFlash(
            'success',
            'La recette '.$recette->getNom().' a été modifiée.'
        );
        // rediriger vers l'affichage des recettes qui comprend le formulaire pour l"ajout d'une nouvelle recette
        return $this->redirectToRoute('app_recette');

    } else {
        // affichage de la liste des recettes avec le formulaire de modification et ses erreurs
        // créer l'objet et le formulaire de création
        $recette = new Recette();
        $formCreation = $this->createForm(RecetteType::class, $recette);
        // lire les recettes
        $lesRecettes = $repository->findAll();
        // rendre la vue
        return $this->render('recette/index.html.twig', [
            'formCreation' => $formCreation->createView(),
            'lesRecettes' => $lesRecettes,
            'formModification' => $form->createView(),
            'idRecetteModif' => $id,
        ]);
    }
}

    /**
    * @Route("/recette/supprimer/{id<\d+>}", name="recette_supprimer")
    */
    public function supprimer(Recette $recette = null, Request $request, EntityManagerInterface $entityManager)
    {
        // vérifier le token
        if ($this->isCsrfTokenValid('action-item'.$recette->getId(), $request->get('_token'))) {
            if ($recette->getProduits()->count() > 0) {
                $this->addFlash(
                    'error',
                    'Il existe des produits dans la recette ' . $recette->getNom() . ', elle ne peut pas être supprimée.'
                );
                return $this->redirectToRoute('app_recette');
            }
            // supprimer la recette
            $entityManager->remove($recette);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'La recette ' . $recette->getNom() . ' a été supprimée.'
            );
        }
        return $this->redirectToRoute('recette');
    }

    // /**
    //  * @Route("/recette/creer", name="recette_creer")
    //  */
    // public function creerRecette(EntityManagerInterface $entityManager): Response
    // {
    //     // : Response        type de retour de la méthode creerRecette
    //     // pour récupérer le EntityManager
    //     //     on peut ajouter l'argument à la méthode comme ici  creerRecette(EntityManagerInterface $entityManager)
    //     //     ou on peut récupérer le EntityManager via $this->getDoctrine() comme ci-dessus en commentaire
    //     //        $entityManager = $this->getDoctrine()->getManager();

    //     // créer l'objet Recette
    //     $recette = new Recette();
    //     $recette->setNom('ratatouille');

    //     // chercher l'id du produit 'aubergine' et l'ajouter à la collection de produits de la recette
    //     $produit = $this->getDoctrine()
    //         ->getRepository(Produit::class)
    //         ->findOneBy(['libelle' => 'aubergine']);
    //     $recette->addProduit($produit);

    //     // chercher l'id du produit 'courgette' et l'ajouter à la collection de produits de la recette
    //     $produit = $this->getDoctrine()
    //         ->getRepository(Produit::class)
    //         ->findOneBy(['libelle' => 'courgettes']);
    //     $recette->addProduit($produit);

    //     // dire à Doctrine que l'objet sera (éventuellement) persisté
    //     $entityManager->persist($recette);

    //     // exécuter les requêtes (indiquées avec persist) ici il s'agit d'ordres INSERT qui seront exécutés
    //     $entityManager->flush();

    //     return new Response('Nouvelle recette enregistrée avec 2 produits, son id est : '.$recette->getId());
    // }



}
