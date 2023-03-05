<?php
    namespace App\Service;

    use PDO;
    use PDOException;

    class ResetDataFromAPIService
    {
        public function resetTablesAndIncrements() {

            // Paramétrage local MySQL
            $host = 'localhost';
            $dbname = 'apiplatform';
            $dbUser = 'root';
            $dbPass = '';

            // Paramétrage hébergeur perso MySQL
            //$host = '185.98.131.93';
            //$dbname = 'devin1226832_5v8lu';
            //$dbUser = 'devin1226832_5v8lu';
            //$dbPass = 'lfwiwdyo6l';
            
            try {
                // Connexion à la bdd:            
                $pdo = new PDO("mysql:host=$host; dbname=$dbname", $dbUser, $dbPass);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Suppression de toutes les données de chacunes des tables
                $sqlO = 'DELETE FROM `orders`';
                $stm = $pdo->prepare($sqlO);
                $stm->execute();

                $sqlP = 'DELETE FROM `products`';
                $stm = $pdo->prepare($sqlP);
                $stm->execute();

                $sqlC = 'DELETE FROM `customers`';
                $stm = $pdo->prepare($sqlC);
                $stm->execute();

                // Remise à zéro des incrémentation d'id
                $sqlO = 'ALTER TABLE `orders` AUTO_INCREMENT = 0';
                $stm = $pdo->prepare($sqlO);
                $stm->execute();

                $sqlP = 'ALTER TABLE `products` AUTO_INCREMENT = 0';
                $stm = $pdo->prepare($sqlP);
                $stm->execute();

                $sqlC = 'ALTER TABLE `customers` AUTO_INCREMENT = 0';
                $stm = $pdo->prepare($sqlC);
                $stm->execute();

                // Le reste sera fait dans le controller directement (il utilise l'autre service)
                // Et on épargne la table Users :o)

            } catch(PDOException $e) {
                die("Connexion MySQL local failed: " . $e->getMessage());
            }           
        }

        public function resetUsersTablesAndIncrements() {

            // Paramétrage local MySQL
            $host = 'localhost';
            $dbname = 'apiplatform';
            $dbUser = 'root';
            $dbPass = '';

            // Paramétrage hébergeur perso MySQL
            //$host = '185.98.131.93';
            //$dbname = 'devin1226832_5v8lu';
            //$dbUser = 'devin1226832_5v8lu';
            //$dbPass = 'lfwiwdyo6l';
            
            try {
                // Connexion à la bdd:            
                $pdo = new PDO("mysql:host=$host; dbname=$dbname", $dbUser, $dbPass);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Suppression de toutes les données de la table des utilisateurs (revendeurs et clients)
                $sqlU = 'DELETE FROM `users`';
                $stm = $pdo->prepare($sqlU);
                $stm->execute();

                // Remise à zéro des incrémentation d'id
                $sqlU = 'ALTER TABLE `customers` AUTO_INCREMENT = 0';
                $stm = $pdo->prepare($sqlU);
                $stm->execute();

            } catch(PDOException $e) {
                die("Connexion MySQL local failed: " . $e->getMessage());
            }           
        }



        
    }