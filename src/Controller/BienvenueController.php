<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BienvenueController extends AbstractController
{
    #[Route('/bienvenue', name: 'app_bienvenue')]
    public function index(): Response
    {
        return $this->render('bienvenue/index.html.twig.');
    }
    #[Route('/bienvenue/{nom}', name: 'app_bienvenue_personne')]
    public function bienvenuePersonne($nom): Response
    {
        return $this->render('bienvenue/bienvenue-personne.html.twig', [
            "nom" => strtoupper($nom)
        ]);
    }
    #[Route('/bienvenus', name: 'app_bienvenus')]
    public function bienvenus(): Response
    {
        // Déclarer un tableau avec 3 prénom
        $prenoms = ["Jean", "Paul", "Jacques"];

        // La vue affiche la bienvenue aux 3 personnes
        return $this->render('bienvenue/bienvenus.html.twig', [
        "prenoms" => $prenoms
    ]);
    }
}
