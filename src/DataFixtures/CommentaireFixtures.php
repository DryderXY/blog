<?php

namespace App\DataFixtures;

use App\Entity\Categorie;
use App\Entity\Commentaire;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CommentaireFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create("fr_FR");

        for($i=0; $i<=20; $i++){
            $commentaire = new Commentaire();
            $commentaire->setContenu($faker->sentence());
            $commentaire->setCreatedAt($faker->dateTimeBetween("-6 months"));
            // Créer une référence sur la catégorie
            $this->addReference("commentaire".$i,$commentaire);
            $manager->persist($commentaire);
        }
        $manager->flush();
    }
}

