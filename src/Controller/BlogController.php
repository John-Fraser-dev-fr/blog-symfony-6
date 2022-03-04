<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Entity\Commentaires;
use App\Entity\Users;
use App\Form\CommentairesType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlogController extends AbstractController
{
    #[Route('/blog', name: 'app_blog')]
    public function index(): Response
    {
        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
        ]);
    }


    #[Route('/articles', name:'all_articles')]
    public function showAll(ManagerRegistry $doctrine) : Response
    {
        $repository= $doctrine->getRepository(Articles::class);
        $articles = $repository->findAll();

        return $this->render('blog/articles.html.twig', 
        ['articles' => $articles]);
    }


    #[Route('/articles/{id}', name:'article')]
    public function show(Request $request, ManagerRegistry $doctrine,EntityManagerInterface $entityManager, int $id) : Response
    {
        $repository=$doctrine->getRepository(Articles::class);
        $article = $repository->find($id);

        //Création nouvel objet Commentaires
        $com = new Commentaires();

        //Création du formulaire lié à l'entité Commentaires
        $formCom = $this->createForm(CommentairesType::class, $com);

        //Analyse de la requête
        $formCom->handleRequest($request);

        if($formCom->isSubmitted() && $formCom->isValid())
        {
            //Récupération de l'id user
            $user = $this->getUser();

            $com->setArticle($article)
                ->setUsers($user);

            //Enregistrement en BDD
            $entityManager->persist($com);
            $entityManager->flush();
        }

        return $this->render('blog/article.html.twig', 
        ['article' => $article,
         'com'=> $com,
         'formCom' => $formCom->createView()]);
    }
}
