<?php

namespace App\Controller;


use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;


class ProduitController extends AbstractController
{
    /**
     * @Route("/produit", name="produit")
     * @Route("/Produit/demandermodification/{id<\d+>}", name="produit_demandermodification")
     */
    public function index($id = null, ProduitRepository $repository, Request $request): Response
    {
        // créer l'objet et le formulaire de création
        $produit = new produit();
        $formCreation = $this->createForm(produitType::class, $produit);
       
           // si 2e route alors $id est renseigné et on  crée le formulaire de modification
   $formModificationView = null;
   if ($id != null) {
       // sécurité supplémentaire, on vérifie le token
       if ($this->isCsrfTokenValid('action-item'.$id, $request->get('_token'))) {
           $produitModif = $repository->find($id);   // le produit  modifier
           $formModificationView = $this->createForm(produitType::class, $produitModif)->createView();
       }
   }


      // lire les produit
      $lesproduits = $repository->findAll();
       return $this->render('produit/index.html.twig', [
          // 'controller_name' => 'produitController',
          'formCreation' => $formCreation->createView(),
           'lesproduits' => $lesproduits,
           'formModification' => $formModificationView,
           'idproduitModif' => $id,

       ]);
   }

   /**
* @Route("/produit/ajouter", name="produit_ajouter")
*/
public function ajouter(produit $produit = null, Request $request, EntityManagerInterface $entityManager, produitRepository $repository)
{
   //  $produit objet de la classe produit, il contiendra les valeurs saisies dans les champs après soumission du formulaire.
   //  $request  objet avec les informations de la requête HTTP (GET, POST, ...)
   //  $entityManager  pour la persistance des données

   // création d'un formulaire de type produitType
   $produit = new produit();
   $form = $this->createForm(produitType::class, $produit);

   // handleRequest met à jour le formulaire
   //  si le formulaire a été soumis, handleRequest renseigne les propriétés
   //      avec les données saisies par l'utilisateur et retournées par la soumission du formulaire
   $form->handleRequest($request);

   if ($form->isSubmitted() && $form->isValid()) {
       // c'est le cas du retour du formulaire
       //         l'objet $produit a été automatiquement "hydraté" par Doctrine
       // dire à Doctrine que l'objet sera (éventuellement) persisté
       $entityManager->persist($produit);
       // exécuter les requêtes (indiquées avec persist) ici il s'agit de l'ordre INSERT qui sera exécuté
       $entityManager->flush();
       // ajouter un message flash de succès pour informer l'utilisateur
       $this->addFlash(
           'success',
           'Le produit ' . $produit->getLibelle() . ' a été ajoutée.'
       );
       // rediriger vers l'affichage des catégories qui comprend le formulaire pour l"ajout d'une nouvelle catégorie
       return $this->redirectToRoute('produit');

   } else {
// affichage de la liste des catégories avec le formulaire de création et ses erreurs
       // lire les catégories
       $lesproduits = $repository->findAll();
       // rendre la vue
       return $this->render('produit/index.html.twig', [
           'formCreation' => $form->createView(),
           'lesproduits' => $lesproduits,
           'formModification' => null,
           'idproduitModif' => null,
       ]);
   }

}

/**
* @Route("/produit/modifier/{id<\d+>}", name="produit_modifier")
*/
public function modifier(produit $produit = null, $id = null, Request $request, EntityManagerInterface $entityManager, produitRepository $repository)
{
   //  Symfony 4 est capable de retrouver la catégorie à l'aide de Doctrine ORM directement en utilisant l'id passé dans la route
   $form = $this->createForm(produitType::class, $produit);
   $form->handleRequest($request);
   if ($form->isSubmitted() && $form->isValid()) {
       // va effectuer la requête d'UPDATE en base de données
       // pas besoin de "persister" l'entité car l'objet a déjà été retrouvé à partir de Doctrine ORM.
       $entityManager->flush();
       $this->addFlash(
           'success',
           'Le produit '.$produit->getLibelle().' a été modifiée.'
       );
       // rediriger vers l'affichage des catégories qui comprend le formulaire pour l"ajout d'une nouvelle catégorie
       return $this->redirectToRoute('produit');

   } else {
       // affichage de la liste des catégories avec le formulaire de modification et ses erreurs
       // créer l'objet et le formulaire de création
       $produit = new produit();
       $formCreation = $this->createForm(produitType::class, $produit);
       // lire les catégories
       $lesproduits = $repository->findAll();
       // rendre la vue
       return $this->render('produit/index.html.twig', [
           'formCreation' => $formCreation->createView(),
           'lesproduits' => $lesproduits,
           'formModification' => $form->createView(),
           'idproduitModif' => $id,
       ]);
   }
}

/**
* @Route("/produit/supprimer/{id<\d+>}", name="produit_supprimer")
*/
public function supprimer(produit $produit = null, Request $request, EntityManagerInterface $entityManager)
{
    // vérifier le token
   if ($this->isCsrfTokenValid('action-item'.$produit->getId(), $request->get('_token'))) {
       if ($produit->getRecettes()->count() > 0) {
           $this->addFlash(
               'error',
               'Il existe des recettes pour les produits ' . $produit->getLibelle() . ', il ne peut pas être supprimée.'
           );
           return $this->redirectToRoute('produit');
       }
       // supprimer la catégorie
       $entityManager->remove($produit);
       $entityManager->flush();
       $this->addFlash(
           'success',
           'Le produit ' . $produit->getLibelle() . ' a été supprimée.'
       );
   }
   return $this->redirectToRoute('produit');
}


}
    


