<?php

namespace App\Controller;


use App\Entity\Produit;
use App\Entity\Categorie;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\ProduitRecherche;
use App\Form\ProduitRechercheType;   
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Knp\Component\Pager\PaginatorInterface;



class ProduitController extends AbstractController
{
    /**
     * @Route("/produit", name="produit")
     * @Route("/produit/menu", name="produit_menu")
     * @Route("/produit/categoriecards/{id<\d+>}", name="produit_categoriecards")
     */
    public function index(Categorie $categorie = null, Request $request, ProduitRepository $repository, SessionInterface $session, PaginatorInterface $paginator): Response
    {
        //récupérer la route courante
        $routeCourante = $request->attributes->get('_route');
        //si demande à partir du menu Produits, on efface les variables de session concernant la recherche
        if($routeCourante == 'produit_menu'){
            $session->remove('Categorie', $categorie);
            $session->remove("ProduitCriteres");

        }else{
            if($categorie != null){
                //mémoriser la catégorie dans une variable de session
                $session->set('Categorie', $categorie);
            }else{
                if($session->has("Categorie")){
                    $categorie = $session->get("Categorie");
                }
            }
        }

      // créer l'objet et le formulaire de recherche
      $produitRecherche = new ProduitRecherche();
      $formRecherche = $this->createForm(ProduitRechercheType::class, $produitRecherche);
      $formRecherche->handleRequest($request);
      if ($formRecherche->isSubmitted() && $formRecherche->isValid()) {
          $produitRecherche = $formRecherche->getData();
          // cherche les produits correspondant aux critères, triés par libellé
          // requête construite dynamiquement alors il est plus simple d'utiliser le querybuilder
          $lesProduits = $repository->findAllByCriteria($produitRecherche, $categorie);
          $session->set('ProduitCriteres', $produitRecherche);
          $lesProduits= $paginator->paginate(
            $repository->findAllByCriteria($produitRecherche , $categorie),
            $request->query->getint('page',1),
            5
        );

        } else {
            // lire les produits
            if ($session->has("ProduitCriteres")) {

                $produitRecherche = $session->get("ProduitCriteres");
                $lesProduits= $paginator->paginate(
                    $repository->findAllByCriteria($produitRecherche, $categorie),
                    $request->query->getint('page',1),
                    5
                );

            } else {
                $p=new ProduitRecherche();
                $lesProduits= $paginator->paginate(
                    $repository->findAllOrderByLibelle($categorie),
                    $request->query->getint('page',1),
                    5
                );

            }
        }

      


      // lire les produit
       return $this->render('produit/index.html.twig', [
          // 'controller_name' => 'produitController',
          'formRecherche' => $formRecherche->createView(),
           'lesProduits' => $lesProduits,
           
       ]);
   }

   /**
* @Route("/produit/ajouter", name="produit_ajouter")
*/
    /**
     * @Route("/produit/ajouter", name="produit_ajouter")
     */
    public function ajouter(Produit $produit=null, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            // cas où le formulaire d'ajout a été soumis par l'utilisateur et est valide
            $produit = $form->getData();
            // on met à jour la base de données 
            $entityManager->persist($produit);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Le produit ' . $produit->getLibelle() . ' a été ajouté.'
            );
            return $this->redirectToRoute('produit');
        } else {
            // cas où l'utilisateur a demandé l'ajout, on affiche le formulaire d'ajout
            return $this->render('produit/ajouter.html.twig', [
                'form' => $form->createView(),
            ]);
        }
    }


/**
 * @Route("/produit/modifier/{id<\d+>}", name="produit_modifier")
 */
public function modifier(Produit $produit = null, Request $request, EntityManagerInterface $entityManager): Response
{
    $form = $this->createForm(ProduitType::class, $produit);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // cas où le formulaire  a été soumis par l'utilisateur et est valide
        //pas besoin de "persister" l'entité : en effet, l'objet a déjà été retrouvé à partir de Doctrine ORM.
        $entityManager->flush();
        $this->addFlash(
            'success',
            'Le produit '.$produit->getLibelle().' a été modifié.'
        );

        return $this->redirectToRoute('produit');
    }
    // cas où l'utilisateur a demandé la modification, on affiche le formulaire pour la modification
    return $this->render('produit/modifier.html.twig', [
        'form' => $form->createView(),
    ]);
}



    /**
     * @Route("/produit/supprimer/{id<\d+>}", name="produit_supprimer")
     */
    public function supprimer(Produit $produit, Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('action-item'.$produit->getId(), $request->get('_token'))) {
            $entityManager->remove($produit);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Le produit '.$produit->getLibelle().' a été supprimé.'
            );

            return $this->redirectToRoute('produit');
        }
    }
}

