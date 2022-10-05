<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;

use App\Repository\CommentaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Constraints\Date;

class ArticleController extends AbstractController
{
    private ArticleRepository $articleRepository;
    private CommentaireRepository $commentaireRepository;

    // Demander à Symfony d'injecter une instance de articleRepository
    // à la création du controller (Instance de articleController)
    public function __construct(ArticleRepository $articleRepository, CommentaireRepository $commentaireRepository){
        $this->articleRepository = $articleRepository;
        $this->commentaireRepository = $commentaireRepository;
    }


    #[Route('/articles', name: 'app_articles')]

        // A l'appel de la méthode, Symfony va créer un objet de la classe ArticleRepository
        // Et le passer en paramètre de la méthode
        // Mécanisme : INJECTION DE DEPENDANCE
    public function getArticles(PaginatorInterface $paginator, Request $request): Response
    {
        // Récupérer les informations  dans la BDD
        // Le controlleur fait appel au modèle (classe du modèle)
        // Afin de récupérer la liste des articles
        // $repository =new ArticleRepository();

        // Mise en place de la pagination
        $articles = $paginator->paginate(
            $this->articleRepository->findBy([],["createdAt" => "DESC"]), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('article/index.html.twig',[
            "articles" => $articles
        ])
            ;
    }

    #[Route('/articles/{slug}', name: 'app_article_slug')]
    public function getArticle($slug): Response
    {
        $article = $this->articleRepository->findOneBy(["slug"=>$slug]);
        $commentaires = $this->commentaireRepository->findBy(["article_id" => $this->articleRepository->findBy(["slug" => $slug])]);

        return $this->render('article/article.html.twig',[
            "article" => $article,
            "commentaires" => $commentaires
        ]);
    }

    #[Route('/articles/nouveau', name: 'app_articles_nouveau', methods: ["GET","POST"] ,priority: 1 )]
    public function insert(SluggerInterface $slugger, Request $request): Response{
        $article = new Article();

        //Création du formulaire
        $formArticle = $this->createForm(ArticleType::class,$article);

        // Reconnaître si le formulaire a été soumis ou non
        $formArticle->handleRequest($request);
        // Est-ce que le formulaire à été soumis
        if ($formArticle->isSubmitted() && $formArticle->isValid()) {
            $article->setSlug($slugger->slug($article->getTitre())->lower())
                    ->setCreatedAt(new \DateTime());
            // Insérer l'article dans la base de données
            $this->articleRepository->add($article,true);
            return $this->redirectToRoute("app_articles");
        }

        // Appel de la vue twig permettant d'afficher le form
        return $this->renderForm("article/nouveau.html.twig",[
            "formArticle"=>$formArticle
        ]);

        /*$article->setTitre("nouvel article 2")
            ->setContenu("Contenu du nouvel article 2")
            ->setSlug($slugger->slug($article->getTitre())->lower())
            ->setCreatedAt(new \DateTime());

        $this->articleRepository->add($article,true);
        return $this->redirectToRoute("app_articles");
        */

    }

}