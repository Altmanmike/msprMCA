<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\NewUserFormType;
use App\Form\NewKeyFormType;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Request $request, EntityManager $entityManager, UsersRepository $usersRepository): Response
    {
        // CAS 1: inscription par mail
        //-----------------------------
        $user1 = new Users();         
        $form1 = $this->createForm(NewUserFormType::class, $user1);
        $form1->handleRequest($request);
        
        if ($form1->isSubmitted() && $form1->isValid()) { 

            $user1->setEmail(htmlspecialchars(trim($form1->get('userEmail')->getData())));
            $user1->setCryptedKey("LA_CLE_A_REMPLACER_PAR_UN_TRUC_COOL");

            $entityManager->persist($user1);
            $entityManager->flush();

            // TODO: Gestion du module mail ici

            return $this->redirectToRoute('app_home');

        }  
        
        // CAS 2: connexion par clé d'authentification
        //--------------------------------------------
        $user2 = new Users();
        $form2 = $this->createForm(NewKeyFormType::class, $user2);
        $form2->handleRequest($request);

        if ($form2->isSubmitted() && $form2->isValid()) {
            if( $form2->get('cryptedKey')->getData() == $usersRepository->getCrytedKey()) {
                // Une fois authentifié le revendeur est redirigé vers l'API
                return $this->redirectToRoute('app_dataHome');
            } else {
                // Sinon
                return $this->redirectToRoute('app_home');
            }
        }

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'newUsersForm' => $form1->createView(),
            'newKeyForm' => $form2->createView()
        ]);


    }

    #[Route('/json', name: 'app_jsonDecode')]
    public function jsonDecode(): Response
    {
        /* ************************************** */
        /* * JUSTE POUR VISUALISER LES DONNEES ** */
        /* ************************************** */        
        $json = file_get_contents("https://615f5fb4f7254d0017068109.mockapi.io/api/v1/customers");
        //$json = file_get_contents("js/customers.json");
        //dd($json);
        $dataDecoded = json_decode($json);        

        return $this->render('home/jsonDecode.html.twig', [
            'controller_name' => 'HomeController',
            'dataDecoded' => $dataDecoded  
        ]);
    }
}
