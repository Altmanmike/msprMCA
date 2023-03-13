<?php

namespace App\Controller;

use PDO;
use PDOException;
use App\Service\ResetDataService;
use App\Service\CryptedKeysService;
use App\Service\FetchWithSleepService;
use App\Service\FetchDataFromAPIService;
use App\Repository\CryptedKeysRepository;
use App\Service\FetchDataFromJSONService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DataController extends AbstractController
{        

    #[Route('/data', name: 'app_dataHome')]
    public function index(): Response
    {/*
        if(!$CryptedKeysService->isCryptedKeys()); {
            $CryptedKeysService->createCryptedKeys();
        }

        $cryptedKeys = $cryptedKeysRepository->findAll();        
        $cryptedKeyR = $cryptedKeys[0];
        $cryptedKeyW = $cryptedKeys[1];   */     

        return $this->render('data/index.html.twig', [
            'controller_name' => 'DataController',
        ]);
    } 

    #[Route('/data/json', name: 'app_dataJson')]
    public function dataJson(): Response
    {
        return $this->render('data/update.html.twig', [
            'controller_name' => 'DataController',
        ]);
    } 

    #[Route('/data/insert', name: 'app_dataInsert')]
    public function dataInsert(FetchDataFromJSONService $fetchDataFromJSONService): Response
    {
        $result = '';
        try {            
            $fetchDataFromJSONService->getDataFromJSON();
            $result = "La base de donnée a correctement été remplie par les données récupérées de l'API";
        } catch(\Exception $e) {
            $result = $e->getMessage();
        }        
        return $this->render('data/index.html.twig', [
            'controller_name' => 'DataController',
            'result' => $result,
        ]);    
    } 

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

        return $this->render('data/index.html.twig', [
            'controller_name' => 'DataController',
            'result' => $result,
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

        return $this->render('data/index.html.twig', [
            'controller_name' => 'DataController',
            'result' => $result,
        ]);
    }  
    
    /************************************************** */
    #[Route('/data/sleep-json', name: 'app_dataSleepJson')]
    public function dataSleepJson(FetchWithSleepService $fetchWithSleepService): Response
    {
        ini_set('max_execution_time', '300'); //300 seconds = 5 minutes
        set_time_limit(300);
        $url = 'https://615f5fb4f7254d0017068109.mockapi.io/api/v1/customers';
        $customers = $fetchWithSleepService->getApiJsonData($url, 2); // Attendre 1 seconde avant d'envoyer la requête
        //dd($customers);

        // Paramétrage local MySQL (mes id/pass de mon MySQL)
        $host = 'localhost';
        $dbname = 'apiplatform';
        $dbUser = 'root';
        $dbPass = '';

        // Paramétrage hébergeur perso MySQL
        //$host = '185.98.131.93';
        //$dbname = 'devin1226832_5v8lu';
        //$dbUser = 'devin1226832_5v8lu';
        //$dbPass = 'lfwiwdyo6l';

        // Création de la chaîne de caractère de connexion à la bdd:
        try {
            // Connexion à la bdd:            
            $pdo = new PDO("mysql:host=$host; dbname=$dbname", $dbUser, $dbPass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Préparons la query une seule fois pour chaque insertion, dans chacune des tables: 
            $sqlCustomers = "INSERT INTO `customers` (`id`, `name`, `username`, `lastname`, `firstname`, `address`, `profile`, `company`, `orders`, `email`, `created_at`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $sthC = $pdo->prepare($sqlCustomers);

            $sqlOrders = "INSERT INTO `orders` (`id`, `customer_id`, `created_at`) VALUES (?, ?, ?)";
            $sthO = $pdo->prepare($sqlOrders);

            $sqlProducts = "INSERT INTO `products` (`id`, `name`, `order_id`, `stock`, `details`, `created_at`) VALUES (?, ?, ?, ?, ?, ?)";
            $sthP = $pdo->prepare($sqlProducts);

        } catch(PDOException $e) {
            die("Connexion MySQL local failed: " . $e->getMessage());
        }  

        // Boucle sur chaque ligne du json, pour chaque Customer..
        foreach ($customers as $customer):
            {                
                if(empty($customer['email'])) {
                    $customer['email'] = '';
                }
                //dd(empty($dataDecode->email));
                //dd(json_encode($dataDecode->address));
                //dd($customer);
                // Pour chacunes des donnnées i.e. chacun des customers, on doit faire un insert dans notre bdd dans une table Customers:                                  
                $sthC->execute([ $customer['id'], $customer['name'], $customer['username'], $customer['lastName'], $customer['firstName'], json_encode($customer['address']), json_encode($customer['profile']), json_encode($customer['company']), json_encode($customer['orders']), $customer['email'], $customer['createdAt'] ]);

                // On récupère les commandes du client pour les insérer aussi dans notre bdd dans une table Orders:
                $dataDecodedOrders = $customer['orders'];
                //dd($dataDecodedOrders);
                foreach ($dataDecodedOrders as $dataDecodeOrder):
                    {
                        $sthO->execute([ $dataDecodeOrder['id'], $dataDecodeOrder['customerId'], $dataDecodeOrder['createdAt'] ]);

                        $idCustomer = $dataDecodeOrder['customerId'];
                        $idOrder = $dataDecodeOrder['id'];

                        // Récupération des produits des commandes du client:
                        $url = "https://615f5fb4f7254d0017068109.mockapi.io/api/v1/customers/$idCustomer/orders/$idOrder/products";
                        $dataDecodedProducts = $fetchWithSleepService->getApiJsonData($url, 2); // Attendre 1 seconde avant d'envoyer la requête    
                        //dd($dataDecodedProducts);                        
                        
                        foreach ($dataDecodedProducts as $dataDecodedProduct):
                            {
                                $sthP->execute([ $dataDecodedProduct['id'], $dataDecodedProduct['name'], $dataDecodedProduct['orderId'], $dataDecodedProduct['stock'], json_encode($dataDecodedProduct['details']), $dataDecodedProduct['createdAt'] ]);
                            }
                        endforeach;
                    }
                endforeach;                   
            }
        endforeach;

        return $this->render('data/index.html.twig', [
            'controller_name' => 'DataController',
        ]);
    } 
}
