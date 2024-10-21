<?php

namespace App\DataFixtures;

use App\Entity\Composant;
use App\Entity\Marque;
use App\Entity\Voiture;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class VoitureFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Création de quelques marques
        $marque1 = new Marque();
        $marque1->setName('Toyota');
        $manager->persist($marque1);

        $marque2 = new Marque();
        $marque2->setName('Tesla');
        $manager->persist($marque2);

        $marque3 = new Marque();
        $marque3->setName('Audi');
        $manager->persist($marque3);

        // Création de quelques composants
        $composant1  = new Composant();
        $composant1->setName('Moteur électrique');
        $manager->persist($composant1);

        $composant2 = new Composant();
        $composant2->setName('Climatisation');
        $manager->persist($composant2);

        $composant3 = new Composant();
        $composant3->setName('GPS');
        $manager->persist($composant3);

        // Création de voiture avec marque et composant
        $voiture1 = new Voiture();
        $voiture1->setModel('Model S');
        $voiture1->setPrice(107999.99);
        $voiture1->setMarque($marque2);
        $voiture1->addComposant($composant1);
        $voiture1->addComposant($composant2);
        $voiture1->addComposant($composant3);
        $manager->persist($voiture1);

        $voiture2 = new Voiture();
        $voiture2->setModel('Yaris');
        $voiture2->setPrice(19999.99);
        $voiture2->setMarque($marque1);
        $voiture2->addComposant($composant2);
        $manager->persist($voiture2);

        $voiture3 = new Voiture();
        $voiture3->setModel('RS6');
        $voiture3->setPrice(169999.99);
        $voiture3->setMarque($marque3);
        $voiture3->addComposant($composant2);
        $voiture3->addComposant($composant3);
        $manager->persist($voiture3);

        $manager->flush();
    }
}
