<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Author;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        
        $manager->flush();
    }
}
