<?php

namespace App\DataFixtures;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\User;

class UserFixtures extends Fixture
{
    private $passwordEncoder;
    
    const ADMIN_USER_REFERENCE = 'admin-user';
    
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
    
    public function load(ObjectManager $manager)
    {   
        
        
        $user = new User();
        $user->setUsername('Admin');
        $user->setName('admin');
        $user->setFirstname('admin');
        $user->setEmail('admin@gmail.com');
        $user->setValid(True);
        $user->setCreateDate(new \DateTime('now'));
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
             'password'
        ));
        $user->setRoles(array('ROLE_ADMIN'));
        $manager->persist($user);

        $manager->flush();
        $this->addReference(self::ADMIN_USER_REFERENCE, $user);
        
    }
}
