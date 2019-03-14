<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\TypePublication;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $typePublication = new TypePublication();
        $typePublication->setName('Citation');
        $typePublication->setValid(true);
        
        $manager->persist($typePublication);
        $manager->flush();
    }
}
