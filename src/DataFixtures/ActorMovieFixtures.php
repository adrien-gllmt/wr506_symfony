<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;
use DateTimeImmutable;
use App\Entity\Actor;
use App\Entity\Movie;

class ActorMovieFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create();
        $faker->addProvider(new \Xylis\FakerCinema\Provider\Person($faker));

        $actors = $faker->actors($gender = null, $count = 120, $duplicates = false);
        $createdActors = [];
        foreach ($actors as $item) {
            $fullname = $item; //ex : Christian Bale
            $fullnameExploded = explode(' ', $fullname);

            $firstname = $fullnameExploded[0]; //ex : Christian
            $lastname = $fullnameExploded[1]; //ex : Bale

            $actor = new Actor();
            $actor->setLastname($lastname);
            $actor->setFirstname($firstname);
            $actor->setBirthday($faker->dateTimeThisCentury());
            $actor->setNationality($this->getReference('nationality_'.rand(1, 6)));

            $createdActors[] = $actor;

            $manager->persist($actor);
        }

        $faker->addProvider(new \Xylis\FakerCinema\Provider\Movie($faker));
        $movies = $faker->movies(100);
        foreach ($movies as $item) {
            $movie = new Movie();
            $movie->setTitle($item);
            $movie->setDuration($faker->runtime);
            $movie->setDescription($faker->overview);
            $movie->setReleaseDate($faker->dateTimeThisCentury());
            $movie->setCategory($this->getReference('category_'.rand(1, 5)));
            $movie->setDirector($faker->director);
            $movie->setBudget(rand(10000, 1000000));
            $movie->setEntries(rand(250, 5000000));
            $movie->setNote(rand(0, 5));

            shuffle($createdActors);
            $createdActorsSliced = array_slice($createdActors, 0, 4);
            foreach ($createdActorsSliced as $actor) {
                $movie->addActor($actor);
            }
            $manager->persist($movie);
        }

        $manager->flush();
        //return true;
    }

    public function getDependencies(): array
    {
        return [
            NationalityFixtures::class,
            CategoryFixtures::class,
        ];
    }
}