<?php

namespace App\DataFixtures;

use App\Entity\Section;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;


class SectionFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr');

        for($i=0; $i<20; $i++){
            $var = new Section();
            $var->setDesignation("SECTION $i" );




            $manager->persist($var);
        }

        $manager->flush();
    }
}
