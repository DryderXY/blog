<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategorieController extends AbstractController
{
    private CategorieRepository $categorieRepository;
    private ArticleRepository $articleRepository;

    public function __construct(CategorieRepository $categorieRepository, ArticleRepository $articleRepository){
        $this->categorieRepository = $categorieRepository;
        $this->articleRepository = $articleRepository;
    }
    #[Route('/categories', name: 'app_categorie')]
    public function getCategories(): Response
    {

        $categories = $this->categorieRepository->findBy([],["titre" => "ASC"]);

        return $this->render('categorie/categories.html.twig',[
            "categories" => $categories
        ])
            ;
    }

    #[Route('/categorie/{slug}', name: 'app_categorie_slug')]

    public function getCategorie($slug): Response
    {
        $categorie = $this->categorieRepository->findOneBy(["slug"=>$slug]);
        $articles = $this->articleRepository->findBy(["categorie"=>$this->categorieRepository->findOneBy(["slug"=>$slug])]);

        return $this->render('categorie/categorie.html.twig',[
            "categorie" => $categorie,
            "articles" => $articles
        ]);
    }

}
