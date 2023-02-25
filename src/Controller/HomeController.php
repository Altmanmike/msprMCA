<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/json', name: 'app_jsonDecode')]
    public function jsonDecode(): Response
    {
        /* ************************************** */
        /* * JUSTE POUR VISUALISER LES DONNEES ** */
        /* ************************************** */        
        $json = file_get_contents("https://615f5fb4f7254d0017068109.mockapi.io/api/v1/customers");
        //$json = file_get_contents("js/customers.json");
        //dd($json);
        $dataDecoded = json_decode($json);        

        return $this->render('home/jsonDecode.html.twig', [
            'controller_name' => 'HomeController',
            'dataDecoded' => $dataDecoded  
        ]);
    }
}
