<?php

namespace App\Controller;

use App\Entity\Articles;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
    public function show(ManagerRegistry $doctrine, int $id) : Response
    {
        $repository=$doctrine->getRepository(Articles::class);
        $article = $repository->find($id);

        return $this->render('blog/article.html.twig', 
        ['article' => $article]);
    }
}
