<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Entity\Categorie;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // Générer une url afin d'acceder à la page d'accueil du CRUD des articles
        $url = $adminUrlGenerator->setController(ArticleCrudController::class)
            ->generateUrl();
        // Rediriger vers cette URL
        return $this->redirect($url);


    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Administration de mon blog');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToUrl("Retour à la maison", "fa fa-home",$this->generateUrl("app_accueil"));
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::section("Article");
        //Créer un sous menu pour les Articles
        yield MenuItem::subMenu("Action", "fas fa-bars")
            ->setSubItems([
                MenuItem::linkToCrud("Lister article", "fas fa-eye", Article::class)
                    ->setAction(Crud::PAGE_INDEX)
                ->setDefaultSort(["createdAt"=>"DESC"]),
                MenuItem::linkToCrud("Ajouter article", "fas fa-plus", Article::class)
                    ->setAction(Crud::PAGE_NEW)
            ]);
        yield MenuItem::section("Categorie");
        //Créer un sous menu pour les categories
        yield MenuItem::subMenu("Action", "fas fa-bars")
            ->setSubItems([
                MenuItem::linkToCrud("Lister categorie", "fas fa-eye", Categorie::class)
                    ->setAction(Crud::PAGE_INDEX),
                MenuItem::linkToCrud("Ajouter categorie", "fas fa-plus", Categorie::class)
                    ->setAction(Crud::PAGE_NEW)
            ]);
    }


}
