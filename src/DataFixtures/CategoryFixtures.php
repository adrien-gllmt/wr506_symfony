<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create();
        $faker->addProvider(new \Xylis\FakerCinema\Provider\Movie($faker));
        $categories = $faker->movieGenres(5); //["Science-fiction", "Drame", "Comédie", "Documentaire", "Action"];
        foreach (range(1, 5) as $i) {
            $category = new Category();
            $category->setName($categories[$i-1]);
            $manager->persist($category);
            $this->addReference('category_'.$i, $category); //expose l'objet à l'extérieur de la classe pour les liaisons avec Movie
        }

        $manager->flush();
    }
}