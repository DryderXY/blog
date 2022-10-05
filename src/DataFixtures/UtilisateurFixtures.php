<?php

namespace App\DataFixtures;

use App\Entity\Utilisateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class UtilisateurFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $faker = Factory::create("fr_FR");
        for ($i=0;$i<50;$i++){
            $utilisateur = new Utilisateur();
            $utilisateur->setNom($faker->firstName);
            $utilisateur->setPrenom($faker->lastName);
            $utilisateur->setPseudo($faker->userName);

            //Créer une référence sur la catégorie
            $this->addReference("utilisateur".$i,$utilisateur);

            $manager->persist($utilisateur);
        }

        $manager->flush();
    }
}
