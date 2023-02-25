<?php
    namespace App\Service;

    use PDO;
    use PDOException;

    class UpdateDataFromAPIService
    {
        public function updateDataFromAPI() {
            // Récupération des données de l'API pour notre mise à jours
            $data = file_get_contents("https://615f5fb4f7254d0017068109.mockapi.io/api/v1/customers");
            $dataDecoded = json_decode($data);

            // Paramétrage local MySQL
            $host = 'http://localhost:3306';
            $dbname = 'apiplateform';
            $dbUser = 'root';
            $dbPass = '';

            // Paramétrage hébergeur perso MySQL
            //$host = '185.98.131.93';
            //$dbname = 'devin1226832_5v8lu';
            //$dbUser = 'devin1226832_5v8lu';
            //$dbPass = 'lfwiwdyo6l';

            // FAIRE L'APPEL DU SERVICE TOUS LES 24 HEURES
        }
    }