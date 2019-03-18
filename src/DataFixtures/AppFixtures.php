<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\TypePublication;
use App\Entity\Publication;
use App\Entity\User;
use App\DataFixtures\UserFixtures;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
       
        $typePublication = new TypePublication();
        $typePublication->setName('Citation');
        $typePublication->setValid(true);
        
        $manager->persist($typePublication);
        $publication = new Publication();
        $publication->setName('Critique');
        $publication->setContent("C'est le bossu qui se moque du chameau");
        $publication->setIdType($typePublication);
        $publication->setAuthor('Le bossu');
        $publication->setCreateDate(new \DateTime);
        $publication->setUser($this->getReference(UserFixtures::ADMIN_USER_REFERENCE));
        $publication->setValid(true);
        $manager->persist($publication);
        
        $publication->setName('Faute');
        $publication->setContent("Faute avoué à moitié pardonnée");
        $publication->setIdType($typePublication);
        $publication->setAuthor('inconnu');
        $publication->setCreateDate(new \DateTime);
        $publication->setUser($this->getReference(UserFixtures::ADMIN_USER_REFERENCE));
        $publication->setValid(true);
        $manager->persist($publication);
        
        $manager->flush();
        
    }
}
