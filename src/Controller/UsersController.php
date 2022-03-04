<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\UsersType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UsersController extends AbstractController
{
    #[Route('/inscription', name: 'inscription')]
    public function inscription(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $encoder): Response
    {

        //Création d'un nouvel objet Users
        $user = new Users();

        //Création du formulaire relié à l'entité Users
        $form = $this->createForm(UsersType::class, $user);

        //Analyse de la requête
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            //hachage du mot de passe
            $hash = $encoder->hashPassword($user,$user->getPassword());
            $user->setPassword($hash);

            //Enregistrement en BDD
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_blog');
        }


        return $this->render('users/inscription.html.twig', [
            'controller_name' => 'UsersController',
            'form' => $form->createView(),
        ]);
    }



    #[Route('/connexion', name:'connexion')]
    public function connexion(AuthenticationUtils $authenticationUtils): Response
    {

        //Obtenir une erreur de connexion s'il y en a une
        $error = $authenticationUtils->getLastAuthenticationError();

    

        return $this->render('users/connexion.html.twig', [
            'error' => $error
        ]);
    }

    #[Route('/deconnexion', name:'deconnexion')]
    public function deconnexion()
    {
        return $this->redirectToRoute('app_blog');
    }
}
