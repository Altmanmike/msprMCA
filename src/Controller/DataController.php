<?php

namespace App\Controller;

use App\Service\FetchDataFromAPIService;
use App\Service\ResetDataService;
use App\Repository\CryptedKeysRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DataController extends AbstractController
{        

    #[Route('/data', name: 'app_dataHome')]
    public function index(CryptedKeysRepository $cryptedKeysRepository): Response
    {
        $cryptedKeys = $cryptedKeysRepository->findAll();

        return $this->render('data/index.html.twig', [
            'controller_name' => 'DataController',
            'cryptedKeys' => $cryptedKeys
        ]);
    } 

    #[Route('/data/json', name: 'app_dataJson')]
    public function dataJson(): Response
    {
        return $this->render('data/update.html.twig', [
            'controller_name' => 'DataController',
        ]);
    } 

    /********************************************************************************************************* */
    /*#[Route('/data-get-from-api', name: 'app_dataGetFromAPI')]
    public function dataSetFromAPI(FetchDataFromAPIService $fetchDataFromAPIService): Response
    {
        $result = '';
        try {            
            $fetchDataFromAPIService->getDataFromAPI();
            $result = "La base de donnée a correctement été remplie par les données récupérées de l'API";
        } catch(\Exception $e) {
            $result = $e->getMessage();
        }        

        return $this->render('data/index.html.twig', [
            'controller_name' => 'DataController',
            'result' => $result
        ]);
    }*/

    /********************************************************************************************************* */

    #[Route('/data-reset-fast', name: 'app_dataResetFast')]
    public function dataResetFast(ResetDataService $resetDataService): Response
    {
        $result = '';
        try {
            $resetDataService->resetTablesAndIncrements();            
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
    public function dataResetUsers(ResetDataService $resetDataService): Response
    {
        $result = '';
        try {
            $resetDataService->resetUsersTablesAndIncrements();            
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
