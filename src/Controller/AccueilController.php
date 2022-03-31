<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\DateTimeZone;


class AccueilController extends AbstractController
{
    /**
     * @Route("/accueil", name="app_accueil")
     */
    public function index(): Response
    {


                // récupérer la variable d'environnement désignant l'URl de l'API
                $urlAPI = $_SERVER['URL_API_OWM'];

                // Initialiser une session CURL
                $clientURL = curl_init();
                // Récupérer le contenu de la page
                curl_setopt($clientURL, CURLOPT_RETURNTRANSFER, 1);
                // Transmettre l'URL
                curl_setopt($clientURL, CURLOPT_URL, $urlAPI);
                // Exécutez la requête HTTP
                $reponse = curl_exec($clientURL);
                // Fermer la session
                curl_close($clientURL);
                // Récupérer les données au format JSON
                $donneesTemps = json_decode($reponse);

                // $dateJour = new DateTime('now', new DateTimeZone('Europe/Paris'));

                //   A COMPLETER
                return $this->render('accueil/index.html.twig', [
                        'donneesTemps' => $donneesTemps,
                        // 'dateJour'=>$dateJour,
                ]);
    }
}