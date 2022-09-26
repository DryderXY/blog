<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use phpDocumentor\Reflection\Types\Array_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    private ArticleRepository $articleRepository;

    // Demander à Symfony d'injecter une instance de articleRepository
    // à la création du controller (Instance de articleController)
    public function __construct(ArticleRepository $articleRepository){
        $this->articleRepository = $articleRepository;
    }


    #[Route('/articles', name: 'app_articles')]

        // A l'appel de la méthode, Symfony va créer un objet de la classe ArticleRepository
        // Et le passer en paramètre de la méthode
        // Mécanisme : INJECTION DE DEPENDANCE
    public function getArticles(): Response
    {
        // Récupérer les informations  dans la BDD
        // Le controlleur fait appel au modèle (classe du modèle)
        // Afin de récupérer la liste des articles
        // $repository =new ArticleRepository();
        $articles = $this->articleRepository->findBy([],["createdAt" => "DESC"]);

        return $this->render('article/index.html.twig',[
            "articles" => $articles
        ])
            ;
    }

    #[Route('/article/{slug}', name: 'app_article_slug')]

    public function getArticle($slug): Response
    {
        $article = $this->articleRepository->findOneBy(["slug"=>$slug]);

        return $this->render('article/article.html.twig',[
            "article" => $article
        ]);
    }
}