<?php
    namespace App\Service;

    use PDO;
    use PDOException;

    class FetchDataFromAPIService
    {             
        public function getDataFromAPI() {

            // Récupération des données de l'API pour notre mise à jours
            $data = file_get_contents("https://615f5fb4f7254d0017068109.mockapi.io/api/v1/customers");
            $dataDecoded = json_decode($data);     

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

            // Création de la chaîne de caractère de connexion à la bdd:
            try {
                // Connexion à la bdd:            
                $pdo = new PDO("mysql:host=$host; dbname=$dbname", $dbUser, $dbPass);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Préparons la query une seule fois pour chaque insertion, dans chacune des tables: 
                $sqlCustomers = "INSERT INTO customers (`id`, `name`, `username`, `lastname`, `firstname`, `address`, `profile`, `company`, `orders`, `created_at`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $sthC = $pdo->prepare($sqlCustomers);

                $sqlOrders = "INSERT INTO orders (`id`, `customer_id`, `createdAt`) VALUES (?, ?, ?)";
                $sthO = $pdo->prepare($sqlOrders);

                $sqlProducts = "INSERT INTO products (`id`, `name`, `order_id`, `stock`, `details`, `created_at`) VALUES (?, ?, ?, ?, ?, ?)";
                $sthP = $pdo->prepare($sqlProducts);

            } catch(PDOException $e) {
                die("Connexion MySQL local failed: " . $e->getMessage());
            }

            // Boucle sur chaque ligne du json, pour chaque Customer..
            foreach ($dataDecoded as $dataDecode):
                { 
                    //dd($dataDecode); 
                    //dd($dataDecode->username);
                    // Pour chacunes des donnnées i.e. chacun des customers, on doit faire un insert dans notre bdd dans une table Customers:              
                    $sthC->execute([ $dataDecode->id, $dataDecode->username, $dataDecode->lastname, $dataDecode->firstname, $dataDecode->address, $dataDecode->profile, $dataDecode->company, $dataDecode->orders, $dataDecode->createdAt ]);

                    // On récupère les commandes du client pour les insérer aussi dans notre bdd dans une table Orders:
                    $dataDecodedOrders = $dataDecode->orders;
                    foreach ($dataDecodedOrders as $dataDecodeOrder):
                        {
                            $sthO->execute([ $dataDecodeOrder->id, $dataDecodeOrder->customerId, $dataDecodeOrder->createdAt ]);

                            $idCustomer = $dataDecodeOrder->customerId;
                            $idOrder = $dataDecodeOrder->id;

                            // Récupération des produits des commandes du client:
                            $dataProducts = file_get_contents("https://615f5fb4f7254d0017068109.mockapi.io/api/v1/customers/'$idCustomer'/orders/'$idOrder'/products");
                            $dataDecodedProducts = json_decode($dataProducts);   
                            foreach ($dataDecodedProducts as $dataDecodedProduct):
                                {
                                    $sthP->execute([ $dataDecodedProduct->id, $dataDecodedProduct->name, $dataDecodedProduct->orderId, $dataDecodedProduct->stock, $dataDecodedProduct->details, $dataDecodedProduct->createdAt ]);
                                }
                            endforeach;
                        }
                    endforeach;                   
                }
            endforeach;
        }
    }