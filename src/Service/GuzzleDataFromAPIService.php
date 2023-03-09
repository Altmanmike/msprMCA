<?php
    namespace App\Service;

    use PDO;
    use PDOException;
    use GuzzleHttp\Client;

    class GuzzleDataFromAPIService
    {             
        public function guzzleDataFromAPI() {

            // Récupération des données de l'API pour notre mise à jours (NODEJS à try pour éviter le flood)
            //$data = file_get_contents("https://615f5fb4f7254d0017068109.mockapi.io/api/v1/customers");
            //$data = file_get_contents("js/customers.json");
            //dd($data);
            //$dataDecoded = json_decode($data); 
            $client = new Client();
            $response = $client->get('https://615f5fb4f7254d0017068109.mockapi.io/api/v1/customers');         
            $dataDecoded = json_decode($response->getBody()->getContents(), true);    
            //dd($dataDecoded);

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
            foreach ($dataDecoded as $dataDecode):
                {                    
                    //dd($dataDecode['id']);
                    //dd(array_key_exists("id", $dataDecode->orders));
                    //dd(array_key_exists("email", $dataDecode));

                    //array_key_exists('email', $dataDecode) ? $dataDecode['email'] : null;
                    //dd(isset($dataDecode['email']));
                    if(!isset($dataDecode['email'])) {
                        $dataDecode['email'] = '';
                        //dd($dataDecode['email']);
                        //dd(isset($dataDecode['email']));
                        $sthC->execute([ $dataDecode['id'], $dataDecode['name'], $dataDecode['username'], $dataDecode['lastName'], $dataDecode['firstName'], json_encode($dataDecode['address']), json_encode($dataDecode['profile']), json_encode($dataDecode['company']), json_encode($dataDecode['orders']), $dataDecode['email'], $dataDecode['createdAt'] ]);
                    } else {                        
                        $sthC->execute([ $dataDecode['id'], $dataDecode['name'], $dataDecode['username'], $dataDecode['lastName'], $dataDecode['firstName'], json_encode($dataDecode['address']), json_encode($dataDecode['profile']), json_encode($dataDecode['company']), json_encode($dataDecode['orders']), $dataDecode['email'], $dataDecode['createdAt'] ]);
                    }

                    // Pour chacunes des donnnées i.e. chacun des customers, on doit faire un insert dans notre bdd dans une table Customers:                     
                    //$sthC->execute([ $dataDecode['id'], $dataDecode['name'], $dataDecode['username'], $dataDecode['lastName'], $dataDecode['firstName'], json_encode($dataDecode['address']), json_encode($dataDecode['profile']), json_encode($dataDecode['company']), json_encode($dataDecode['orders']), $dataDecode['email'], $dataDecode['createdAt'] ]); 
                    
                    // On récupère les commandes du client pour les insérer aussi dans notre bdd dans une table Orders:
                    $dataDecodedOrders = $dataDecode['orders'];
                    //dd($dataDecodedOrders);
                    foreach ($dataDecodedOrders as $dataDecodeOrder):
                        {
                            $sthO->execute([ $dataDecodeOrder['id'], $dataDecodeOrder['customerId'], $dataDecodeOrder['createdAt'] ]);

                            $idCustomer = $dataDecodeOrder['customerId'];
                            $idOrder = $dataDecodeOrder['id'];
                            
                            // Récupération des produits des commandes du client:
                            $client2 = new Client();
                            $response = $client2->get("https://615f5fb4f7254d0017068109.mockapi.io/api/v1/customers/$idCustomer/orders/$idOrder/products");        
                            $dataDecodedProducts = json_decode($response->getBody()->getContents(), true); 
                            //dd($dataDecodedProducts);
                            //$dataProducts = file_get_contents("https://615f5fb4f7254d0017068109.mockapi.io/api/v1/customers/$idCustomer/orders/$idOrder/products");
                            //dd($dataProducts);
                            //$dataDecodedProducts = json_decode($dataProducts);   
                            foreach ($dataDecodedProducts as $dataDecodedProduct):
                                {
                                    $sthP->execute([ $dataDecodedProduct['id'], $dataDecodedProduct['name'], $dataDecodedProduct['orderId'], $dataDecodedProduct['stock'], json_encode($dataDecodedProduct['details']), $dataDecodedProduct['createdAt'] ]);
                                }
                            endforeach;
                        }
                    endforeach;                   
                }
            endforeach;
        }
    }