<?php

namespace App\Controller;

use App\Service\FetchDataFromAPIService;
use App\Service\ResetDataFromAPIService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DataController extends AbstractController
{
    #[Route('/data-get-from-api', name: 'app_dataGetFromAPI')]
    public function dataGetFromAPI(FetchDataFromAPIService $fetchDataFromAPIService): Response
    {
        $result = '';
        try {            
            $fetchDataFromAPIService->getDataFromAPI();
            $result = "La base de donnée a correctement été remplie par les données récupérées de l'API";
        } catch(\Exception $e) {
            $result = $e->getMessage();
        }        

        return $this->render('data/update.html.twig', [
            'controller_name' => 'DataController',
            'result' => $result
        ]);
    }

    #[Route('/data-update-from-api', name: 'app_dataUpdateFromAPI')]
    public function dataUpdateFromAPI(ResetDataFromAPIService $resetDataFromAPIService, FetchDataFromAPIService $fetchDataFromAPIService): Response
    {
        $result = '';
        try {
            $resetDataFromAPIService->resetTablesAndIncrements();
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
