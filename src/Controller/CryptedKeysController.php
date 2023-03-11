<?php

namespace App\Controller;

use App\Service\CryptedKeysService;
use App\Repository\CryptedKeysRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CryptedKeysController extends AbstractController
{
    #[Route('/crypted_keys/new', name: 'app_crypted_keys_new')]
    public function index(CryptedKeysService $cryptedKeysService, CryptedKeysRepository $cryptedKeysRepository): Response
    {
        if($cryptedKeysService->isCryptedKeys() !== 0); {
            $cryptedKeysService->createCryptedKeys();
        }

        $cryptedKeys = $cryptedKeysRepository->findAll();        
        $cryptedKeyR = $cryptedKeys[0];
        $cryptedKeyW = $cryptedKeys[1];   

        return $this->render('data/index.html.twig', [
            'controller_name' => 'DataController',
            'cryptedKeyR' => $cryptedKeyR,
            'cryptedKeyW' => $cryptedKeyW
        ]);
    }

    #[Route('/crypted/key/revendeur', name: 'app_crypted_key_revendeur')]
    public function cryptedKeyRevendeur(CryptedKeysRepository $cryptedKeysRepository): Response
    {
        // TODO: renvoyer par Mail à tous les utilisateurs revendeur la nouvelle clé
        /*$keys = $cryptedKeysRepository->findAll();
        $newKeyR = substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789',10)),0,25);
        $keys->Revendeur->setCle() = $newKeyR;
        $entityManager->persist($keys);
        $entityManager->flush();
        $cryptedKeys = $cryptedKeysRepository->findAll();
        $cryptedKeyR = $cryptedKeys[0];
        $cryptedKeyW = $cryptedKeys[1];*/

        return $this->render('data/index.html.twig', [
            'controller_name' => 'CryptedKeysController',/*
            'cryptedKeyR' => $cryptedKeyR,
            'cryptedKeyW' => $cryptedKeyW*/
        ]);
    }

    #[Route('/crypted_key/webshop', name: 'app_crypted_key_webshop')]
    public function cryptedKeyWebshop(): Response
    {
        // TODO: renvoyer par Mail à tous les utilisateurs webshop la nouvelle clé
        /*$keys = $cryptedKeysRepository->findAll();
        $newKeyW = substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789',10)),0,25);
        $keys->Webshop->setCle() = $newKeyW;
        $entityManager->persist($keys);
        $entityManager->flush();
        $cryptedKeys = $cryptedKeysRepository->findAll();
        $cryptedKeyR = $cryptedKeys[0];
        $cryptedKeyW = $cryptedKeys[1];*/

        return $this->render('data/index.html.twig', [
            'controller_name' => 'CryptedKeysController',/*
            'cryptedKeyR' => $cryptedKeyR,
            'cryptedKeyW' => $cryptedKeyW */           
        ]);
    }

    #[Route('/crypted_keys/reset', name: 'app_crypted_keysReset')]
    public function cryptedKeysReset(CryptedKeysRepository $cryptedKeysRepository, CryptedKeysService $cryptedKeysService): Response
    {
        $cryptedKeyR = '';
        $cryptedKeyW = '';
        $cryptedKeys = $cryptedKeysRepository->findAll();  

        if(!empty($cryptedKeys)) {
            $cryptedKeyR = $cryptedKeys[0];
            $cryptedKeyW = $cryptedKeys[1];
        }     
        
        $result = '';
        try {
            $cryptedKeysService->createCryptedKeysTableAndIncrements();            
            $result = "Les données de la table CryptedKey ont correctement été effacées et réinitialisées";
        } catch(\Exception $e) {
            $result = $e->getMessage();
        }        

        return $this->render('data/index.html.twig', [
            'controller_name' => 'DataController',
            'result' => $result,
            'cryptedKeyR' => $cryptedKeyR,
            'cryptedKeyW' => $cryptedKeyW
        ]);
    }  
    
    #[Route('/crypted_keys/show', name: 'app_crypted_keys_show')]
    public function cryptedKeysShow(CryptedKeysRepository $cryptedKeysRepository, CryptedKeysService $cryptedKeysService): Response
    {
        $cryptedKeyR = '';
        $cryptedKeyW = '';
        $cryptedKeys = $cryptedKeysRepository->findAll();  
        //dd($cryptedKeys[0]);
        
        $cryptedKeyR = $cryptedKeys[0];
        $cryptedKeyW = $cryptedKeys[1];
/*
        if(!empty($cryptedKeys)) {
            $cryptedKeyR = $cryptedKeys[0];
            $cryptedKeyW = $cryptedKeys[1];
        }   */ 
        //dd($cryptedKeys);
        //dd($cryptedKeys[0]);
        return $this->render('crypted_keys/index.html.twig', [
            'controller_name' => 'DataController',            
            'cryptedKeyR' => $cryptedKeyR,
            'cryptedKeyW' => $cryptedKeyW
        ]);
    } 
}
