<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Episode;
use App\Entity\Program;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;


class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {

        //Puis ici nous demandons à la Factory de nous fournir un Faker

        $faker = Factory::create();


        for ($i = 0; $i < 5; $i++) {
            for ($j = 1; $j < 6; $j++) {
                for ($k = 1; $k < 11; $k++) {


                    $episode = new Episode();
                    //Ce Faker va nous permettre d'alimenter l'instance de episode que l'on souhaite ajouter en base
                    $episode->setNumber($k);
                    $episode->setTitle($faker->words(3, true));
                    $episode->setSynopsis($faker->paragraph());
                    $episode->setSeason($this->getReference('program_' . $i . '_season_' . $j));
                    $manager->persist($episode);
                }
            }
        }


        $manager->flush();
    }


    public function getDependencies()
    {
        // Tu retournes ici toutes les classes de fixtures dont ProgramFixtures dépend
        return [
            ProgramFixtures::class
        ];
    }
}