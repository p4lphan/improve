<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\TypePublication;
use App\Entity\Publication;
use App\DataFixtures\UserFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class AppFixtures extends Fixture implements DependentFixtureInterface
{
    
    
    public function load(ObjectManager $manager)
    {
        $user = $this->getReference(UserFixtures::ADMIN_USER_REFERENCE);
        $typePublication = new TypePublication();
        $typePublication->setName('Citation');
        $typePublication->setValid(true);
        
        $manager->persist($typePublication);
        
        $publication = new Publication();
        $publication->setName('Critique');
        $publication->setContent("C'est le bossu qui se moque du chameau");
        $publication->setIdType($typePublication);
        $publication->setAuthor('inconnu');
        $publication->setCreateDate(new \DateTime);
        $publication->setUser($user);
        $publication->setFilepath('images/pic01.jpg');
        $publication->setValid(true);
        $manager->persist($publication);
        
        $publication2 = new Publication();
        $publication2->setName('Faute');
        $publication2->setContent("Faute avoué à moitié pardonnée");
        $publication2->setIdType($typePublication);
        $publication2->setAuthor('inconnu');
        $publication2->setCreateDate(new \DateTime);
        $publication2->setUser($user);
        $publication2->setFilepath('images/pic02.jpg');
        $publication2->setValid(true);
        $manager->persist($publication2);
        
        $manager->flush();
        
    }
    
    public function getDependencies()
    {
        return array(
            UserFixtures::class,
        );
    }
}
