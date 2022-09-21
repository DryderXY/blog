<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Initialiser Faker
        $faker = Factory::create("fr_FR");

        for ($i=0;$i<50;$i++){
            $article = new Article();
            $article->setTitre($faker->words($faker->numberBetween(3,10),true));
            $article->setContenu($faker->paragraphs(3,true));
            $article->setCreatedAt($faker->dateTimeBetween("-6 months"));

            // Generer l'ordre INSERT dans la base
            // INSERT INTO article values ("Titre","Contenu"..)
            $manager->persist($article);
        }

        // Envoyer l'ordre INSERT vers la base
        $manager->flush();
    }
}