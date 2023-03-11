<?php
    namespace App\Service;

    use PDO;
    use PDOException;

class CryptedKeysService
{
    public function isCryptedKeys(): bool
    {
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

            // On compte le nombre de ligne dans la table Crypted_Keys 
            $sql = "SELECT COUNT(*) FROM `crypted_keys`";
            $sth = $pdo->prepare($sql);
            $sth->execute();
            $nb = $sth->fetch();
            //dd($nb);

            if($nb) {
                return true;
            } else {
                return false;
            }

        } catch(PDOException $e) {
            die("Connexion MySQL local failed: " . $e->getMessage());
        }
    }

    public function createCryptedKeys()
    {
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

            // Suppression de toutes les données de la table des utilisateurs (revendeurs et clients)
            $sql = 'DELETE FROM `crypted_keys`';
            $stm = $pdo->prepare($sql);
            $stm->execute();

            // Remise à zéro des incrémentation d'id
            $sql = 'ALTER TABLE `crypted_keys` AUTO_INCREMENT = 0';
            $stm = $pdo->prepare($sql);
            $stm->execute();

            $revendeur = 'Revendeur';
            $webshop = 'Webshop';
            $keyRevendeur = substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789',10)),0,25);
            $keyWebshop = substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789',10)),0,25);

            // On compte le nombre de ligne dans la table Crypted_Keys 
            $sql = "INSERT INTO `crypted_keys`(`nom`, `cle`) VALUES (?, ?),(?, ?)";
            $sth = $pdo->prepare($sql);
            $sth->execute( [ $revendeur, $keyRevendeur, $webshop, $keyWebshop ] );

        } catch(PDOException $e) {
            die("Connexion MySQL local failed: " . $e->getMessage());
        }  
    }

    public function createCryptedKeysTableAndIncrements() {

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

            // Suppression de toutes les données de la table des clés cryptés
            $sqlK = 'DELETE FROM `crypted_keys`';
            $stm = $pdo->prepare($sqlK);
            $stm->execute();

            // Remise à zéro des incrémentation d'id
            $sqlK = 'ALTER TABLE `crypted_keys` AUTO_INCREMENT = 0';
            $stm = $pdo->prepare($sqlK);
            $stm->execute();

        } catch(PDOException $e) {
            die("Connexion MySQL local failed: " . $e->getMessage());
        }           
    } 
}