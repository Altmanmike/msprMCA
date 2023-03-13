<?php
namespace App\Service;

use PDO;
use PDOException;
use Symfony\Component\HttpClient\HttpClient;

    class FetchWithSleepService
    {            
        public function getApiJsonData($url, $sleepTime = 0)
        {       
            $httpClient = HttpClient::create();

            // Ajouter un temps de sommeil pour éviter les requêtes excessives
            if ($sleepTime > 0) {
                sleep($sleepTime);
            }            

            $response = $httpClient->request('GET', $url);
            $statusCode = $response->getStatusCode();

            if ($statusCode !== 200) {
                throw new \Exception('Erreur lors de la récupération des données de l\'API : '.$statusCode);
            }

            $content = $response->getContent();

            // Convertir le contenu JSON en tableau associatif
            $data = json_decode($content, true);

            return $data;          
        }    
    }