<?php

namespace App\Controller;

use App\Repository\CryptedKeysRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CryptedKeysController extends AbstractController
{
    #[Route('/crypted/key/revendeur', name: 'app_crypted_key_revendeur')]
    public function cryptedKeyRevendeur(CryptedKeysRepository $cryptedKeysRepository): Response
    {
        // TODO: renvoyer par Mail à tous les utilisateurs revendeur la nouvelle clé
        //$keys = $cryptedKeysRepository->findAll();
        //$newKeyR = substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789',10)),0,25);
        //$keys->Revendeur->setCle() = $newKeyR;

        return $this->render('crypted_keys/index.html.twig', [
            'controller_name' => 'CryptedKeysController'/*,
            'newKeyR' => $newKeyR*/
        ]);
    }

    #[Route('/crypted/key/webshop', name: 'app_crypted_key_webshop')]
    public function cryptedKeyWebshop(): Response
    {
        // TODO: renvoyer par Mail à tous les utilisateurs webshop la nouvelle clé
        //$keys = $cryptedKeysRepository->findAll();
        //$newKeyW = substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789',10)),0,25);
        //$keys->Webshop->setCle() = $newKeyW;

        return $this->render('crypted_keys/index.html.twig', [
            'controller_name' => 'CryptedKeysController'/*,
            'newKeyW' => $newKeyW*/
        ]);
    }
}
