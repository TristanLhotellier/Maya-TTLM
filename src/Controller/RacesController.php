<?php

namespace App\Controller;

use App\Entity\Race;
use App\Form\RacesType;
use App\Repository\RaceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RacesController extends AbstractController
{
    /**
     * @Route("/races", name="races")
     * @Route("/races/demandermodification/{id<\d+>}", name="races_demandermodification")
     */
    public function index($id = null, RaceRepository $repository, Request $request): Response
    {
         // créer l'objet et le formulaire de création
         $race = new Race();
         $formCreation = $this->createForm(RacesType::class, $race);

        // si 2e route alors $id est renseigné et on  crée le formulaire de modification
        $formModificationView = null;

        if ($id != null) {
            // sécurité supplémentaire, on vérifie le token
            if ($this->isCsrfTokenValid('action-item'.$id, $request->get('_token'))) {
                $raceModif = $repository->find($id);   // la catégorie à modifier
                $formModificationView = $this->createForm(RacesType::class, $raceModif)->createView();
            }
        }

        //lire les races

        $lesRaces = $repository->findAll();

        return $this->render('races/index.html.twig', [
            'formCreation' => $formCreation->createView(),
            'lesRaces' => $lesRaces,
            'formModification' => $formModificationView,
            'idRaceModif' => $id,
        ]);
    }

    /**
     * @Route("/races/ajouter", name="races_ajouter")
     */
    public function ajouter(Race $race = null, Request $request, EntityManagerInterface $entityManager, RaceRepository $repository)
    {
        //  $categorie objet de la classe Categorie, il contiendra les valeurs saisies dans les champs après soumission du formulaire.
        //  $request  objet avec les informations de la requête HTTP (GET, POST, ...)
        //  $entityManager  pour la persistance des données

        // création d'un formulaire de type CategorieType
        $race = new Race();
        $form = $this->createForm(RacesType::class, $race);

        // handleRequest met à jour le formulaire
        //  si le formulaire a été soumis, handleRequest renseigne les propriétés
        //      avec les données saisies par l'utilisateur et retournées par la soumission du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // c'est le cas du retour du formulaire
            //         l'objet $race a été automatiquement "hydraté" par Doctrine
            // dire à Doctrine que l'objet sera (éventuellement) persisté
            $entityManager->persist($race);
            // exécuter les requêtes (indiquées avec persist) ici il s'agit de l'ordre INSERT qui sera exécuté
            $entityManager->flush();
            // ajouter un message flash de succès pour informer l'utilisateur
            $this->addFlash(
                'success',
                'La race ' . $race->getLibelle() . ' a été ajoutée.'
            );
            // rediriger vers l'affichage des catégories qui comprend le formulaire pour l"ajout d'une nouvelle catégorie
            return $this->redirectToRoute('races');

        } else {
            // affichage de la liste des catégories avec le formulaire de création et ses erreurs
            // lire les catégories
            $lesRaces = $repository->findAll();
            // rendre la vue
            return $this->render('races/index.html.twig', [
                'formCreation' => $form->createView(),
                'lesRaces' => $lesRaces,
                'formModification' => null,
                'idRaceModif' => null,
            ]);
        }

    }

    /**
     * @Route("/races/modifier/{id<\d+>}", name="races_modifier")
     */
    public function modifier(Race $race = null, $id = null, Request $request, EntityManagerInterface $entityManager, RaceRepository $repository)
    {
        //  Symfony 4 est capable de retrouver la catégorie à l'aide de Doctrine ORM directement en utilisant l'id passé dans la route
        $form = $this->createForm(RacesType::class, $race);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // va effectuer la requête d'UPDATE en base de données
            // pas besoin de "persister" l'entité car l'objet a déjà été retrouvé à partir de Doctrine ORM.
            $entityManager->flush();
            $this->addFlash(
                'success',
                'La race '.$race->getLibelle().' a été modifiée.'
            );
            // rediriger vers l'affichage des catégories qui comprend le formulaire pour l"ajout d'une nouvelle catégorie
            return $this->redirectToRoute('races');

        } else {
            // affichage de la liste des catégories avec le formulaire de modification et ses erreurs
            // créer l'objet et le formulaire de création
            $race = new Race();
            $formCreation = $this->createForm(RacesType::class, $race);
            // lire les catégories
            $lesRaces = $repository->findAll();
            // rendre la vue
            return $this->render('races/index.html.twig', [
                'formCreation' => $formCreation->createView(),
                'lesRaces' => $lesRaces,
                'formModification' => $form->createView(),
                'idRaceModif' => $id,
            ]);
        }
    }

    /**
     * @Route("/races/supprimer/{id<\d+>}", name="races_supprimer")
     */
    public function supprimer(Race $race = null, Request $request, EntityManagerInterface $entityManager)
    {
        // vérifier le token
        if ($this->isCsrfTokenValid('action-item'.$race->getId(), $request->get('_token'))) {
            
            // supprimer la catégorie
            $entityManager->remove($race);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'La catégorie ' . $race->getLibelle() . ' a été supprimée.'
            );
        }
        return $this->redirectToRoute('races');
    }
}
