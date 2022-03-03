<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\UsersType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

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


        return $this->render('users/index.html.twig', [
            'controller_name' => 'UsersController',
            'form' => $form->createView(),
        ]);
    }
}
