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

    #[Route('/jsonDecode', name: 'app_parse')]
    public function jsonDecode(): Response
    {
        //$json = file_get_contents("/test_API_plateform/API-Platform/public/js/customers.json");
        $json = file_get_contents("js/customers.json");
        //dd($json);
        $dataDecoded = json_decode($json);        

        return $this->render('home/jsonDecode.html.twig', [
            'controller_name' => 'HomeController',
            'dataDecoded' => $dataDecoded  
        ]);
    }
}
