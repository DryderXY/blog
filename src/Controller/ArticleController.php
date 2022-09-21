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
    #[Route('/articles', name: 'app_articles')]

        // A l'appel de la méthode, Symfony va créer un objet de la classe ArticleRepository
        // Et le passer en paramètre de la méthode
        // Mécanisme : INJECTION DE DEPENDANCE
    public function getArticles(ArticleRepository $repository): Response
    {
        // Récupérer les informations  dans la BDD
        // Le controlleur fait appel au modèle (classe du modèle)
        // Afin de récupérer la liste des articles
        // $repository =new ArticleRepository();
        $articles = $repository->findBy([],["createdAt" => "DESC"],5);

        return $this->render('article/index.html.twig',[
            "articles" => $articles
        ]);
    }
}
