<?php

namespace App\DataFixtures;

use App\Entity\Users;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UsersFixtures extends Fixture
{

    public function __construct(private UserPasswordHasherInterface $hashe) {}

    public function load(ObjectManager $manager): void
    {
        
        $admin = new Users();
        $admin->setEmail('admin@boca.com')
            ->setPassword($this->hashe->hashPassword($admin, 'Azerty&-0123'))
            ->setRoles(["ROLE_ADMIN"])
        ;
        $manager->persist($admin);
        $manager->flush();
    }
}
