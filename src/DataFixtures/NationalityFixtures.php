<?php

namespace App\DataFixtures;

use App\Entity\Nationality;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class NationalityFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $nationalities = ['Français', 'Américain', 'Anglais', 'Allemand', 'Espagnol', 'Italien'];

        foreach (range(1, 6) as $i) {
            $nationality = new Nationality();
            $nationality->setName($nationalities[$i-1]);
            $manager->persist($nationality);
            $this->addReference('nationality_'.$i, $nationality); //expose l'objet à l'extérieur de la classe pour les liaisons avec Movie
        }

        $manager->flush();
    }
}
