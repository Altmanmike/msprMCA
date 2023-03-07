<?php

namespace App\Controller;

use App\Service\FetchDataFromAPIService;
use App\Service\ResetDataFromAPIService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DataController extends AbstractController
{        
    #[Route('/data', name: 'app_dataHome')]
    public function index(): Response
    {
        return $this->render('data/index.html.twig', [
            'controller_name' => 'DataController'
        ]);
    } 

    #[Route('/data-get-json', name: 'app_dataGetJsonFromAPI')]
    public function dateGetJsonFromAPI(): Response
    {
        // TODO: Récupérer les données de l'API MOOC au format .json  à placer dans public/js/ pour l'instant en local
        return $this->redirectToRoute('app_dataHome'); 
        //return $this->redirectToRoute('app_dataJsonFile'); 
    } 

    #[Route('/data-set-from-api', name: 'app_dataSetFromAPI')]
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
            $result = "La base de données a correctement été mise à jour par l'API";
        } catch(\Exception $e) {
            $result = $e->getMessage();
        }        

        return $this->render('data/update.html.twig', [
            'controller_name' => 'DataController',
            'result' => $result
        ]);
    }

    #[Route('/data-reset-fast', name: 'app_dataResetFast')]
    public function dataResetFast(ResetDataFromAPIService $resetDataFromAPIService, FetchDataFromAPIService $fetchDataFromAPIService): Response
    {
        $result = '';
        try {
            $resetDataFromAPIService->resetTablesAndIncrements();            
            $result = "Les données de la base ont correctement été effacées et réinitialisées";
        } catch(\Exception $e) {
            $result = $e->getMessage();
        }        

        return $this->render('data/update.html.twig', [
            'controller_name' => 'DataController',
            'result' => $result
        ]);
    }

    #[Route('/data-reset-users', name: 'app_dataResetUsers')]
    public function dataResetUsers(ResetDataFromAPIService $resetDataFromAPIService, FetchDataFromAPIService $fetchDataFromAPIService): Response
    {
        $result = '';
        try {
            $resetDataFromAPIService->resetUsersTablesAndIncrements();            
            $result = "Les données de la table Users ont correctement été effacées et réinitialisées";
        } catch(\Exception $e) {
            $result = $e->getMessage();
        }        

        return $this->render('data/update.html.twig', [
            'controller_name' => 'DataController',
            'result' => $result
        ]);
    }   
}
