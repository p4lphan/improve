<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\TypePublication;
use App\Entity\Publication;
use App\Entity\User;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['userName' => 'Admin']);
        
        $typePublication = new TypePublication();
        $typePublication->setName('Citation');
        $typePublication->setValid(true);
        
        $manager->persist($typePublication);
        $manager->flush();
        
        $publication = new Publication();
        $publication->setName('Critique');
        $publication->setContent("C'est le bossu qui se moque du chameau");
        $publication->setIdType($typePublication);
        $publication->setAuthor('Le bossu');
        $publication->setCreateDate(new \DateTime);
        $publication->setUser($user);
        $publication->setValid(true);
        
        $manager->persist($publication);
        $manager->flush();
        
        $publication->setName('Critique');
        $publication->setContent("C'est le bossu qui se moque du chameau");
        $publication->setIdType($typePublication);
        $publication->setAuthor('Le bossu');
        $publication->setCreateDate(new \DateTime);
        $publication->setUser($user);
        $publication->setValid(true);
        
        
        $manager->persist($publication);
        $manager->flush();
    }
}
