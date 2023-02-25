<?php

namespace App\Controller;

use App\Service\FetchDataFromAPIService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DataController extends AbstractController
{
    #[Route('/data-update', name: 'app_dataUpdate')]
    public function dataUpdate(FetchDataFromAPIService $fetchDataFromAPIService): Response
    {
        try {
            $fetchDataFromAPIService->getDataFromAPI();
            $result = "La base de donnée a correctement été mise à jour par l'API";
        } catch(\Exception $e) {
            $result = $e->getMessage();
        }        

        return $this->render('data/update.html.twig', [
            'controller_name' => 'DataController',
            'result' => $result
        ]);
    }

    #[Route('/data', name: 'app_dataHome')]
    public function index(): Response
    {
        return $this->render('data/index.html.twig', [
            'controller_name' => 'DataController'
        ]);
    }    
}
