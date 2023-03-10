<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\NewUserFormType;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CryptedKeysRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Request $request, EntityManagerInterface $entityManager, UsersRepository $usersRepository, CryptedKeysRepository $cryptedKeysRepo): Response
    {
        // m pour message
        if( !isset($_GET['m']) ) {
            $m = '';
        } else {
            $m = $_GET['m'];
        }       

        // CAS 1: inscription par mail
        //-----------------------------
        $user1 = new Users();         
        $form1 = $this->createForm(NewUserFormType::class, $user1);
        $form1->handleRequest($request);
               
        if ($form1->isSubmitted() && $form1->isValid()) {             
            $destinataire = htmlspecialchars(trim($form1->get('email')->getData()));

            // Check si le mail est déjà présent dans la base
            $users = $usersRepository->findAll();              

            foreach($users as $u): {
                if($destinataire == $u->getEmail()) {                    
                    $m = "Un compte existe déjà pour cet utilisateur";                    
                    return $this->redirectToRoute('app_home', array(
                        'm' => $m
                    ));
                }
            }
            endforeach;
            
            $user1->setEmail($destinataire);  
            $user1->setRoles(['ROLE_USER']);         
            $user1->setCreatedAt(new \DateTimeImmutable());      
            
            $entityManager->persist($user1);
            $entityManager->flush();

            // TODO: Gestion du module mail:    
            $keys = $cryptedKeysRepo->findAll();  

            $expediteur = "altman.mikeepsi@gmail.fr";  //"no-reply@payetonkawa.fr";         
            $sujet = "Inscription réussi, voici votre clé d'authentification";            
            $message = '<div style="margin:auto; padding:200px"><h1 style="margin:20px">Votre clé d\'identification</h1><p>'.$keys.'</p></div>';
            // En-têtes de l'email
            $headers = "From: $destinataire" . "\r\n" .
                    "Reply-To: $expediteur" . "\r\n" .
                    "X-Mailer: PHP/" . phpversion();
            // Envoi de l'email
            mail($destinataire, $sujet, $message, $headers);
            //$mess_en_pop_a_faire = "La clé d'authentification a été envoyée à l'adresse e-mail $email.";
            return $this->redirectToRoute('app_home');

        } 

        // CAS 2: connexion par clé d'authentification
        //--------------------------------------------   
        // si tout foire remettre le form et l'affilier à la bonne class 
        if (isset($_GET['cryptedKey'])) {
            $cryptedKey = htmlspecialchars(trim($request->get('cryptedKey')->getData()));
            $keys = $cryptedKeysRepo->findAll();

            if(in_array($cryptedKey, $keys)) {
                // Une fois authentifié le revendeur est redirigé vers l'API
                return $this->redirect('/api');
            } else {
                // Sinon
                return $this->redirectToRoute('app_home');
            }
        }               
        
        // CAS 2: connexion par clé d'authentification
        //--------------------------------------------        
        /*$crypt = new Users();         
        $form2 = $this->createForm(NewKeyFormType::class, $crypt);
        
        $form2->handleRequest($request);
        
        if ($form2->isSubmitted() && $form2->isValid()) {                  

            $cryptedKey = htmlspecialchars(trim($request->get('cryptedKey')->getData()));
            $keys = $cryptedKeysRepo->findAll();

            if(in_array($cryptedKey, $keys)) {
                // Une fois authentifié le revendeur est redirigé vers l'API
                return $this->redirectToRoute('app_dataHome');
            } else {
                // Sinon
                return $this->redirectToRoute('app_home');
            }
        }*/

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'newUserForm' => $form1->createView(),
            'm' => $m           
        ]);
    }

    #[Route('/json', name: 'app_jsonDecode')]
    public function jsonDecode(): Response
    {
        /* ************************************** */
        /* * JUSTE POUR VISUALISER LES DONNEES ** */
        /* ************************************** */   
        // Faut trouver une méthode qui enregistrerait la réponse de l'url en .json dans le dossier public.     
        //$json = file_get_contents("https://615f5fb4f7254d0017068109.mockapi.io/api/v1/customers");
        $json = file_get_contents("js/customers.json");
        //dd($json);
        $dataDecoded = json_decode($json);        

        return $this->render('home/jsonDecode.html.twig', [
            'controller_name' => 'HomeController',
            'dataDecoded' => $dataDecoded  
        ]);
    }

    #[Route(path: '/deconnexion', name: 'app_logout')]
    public function logout(): void
    {
        //throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
