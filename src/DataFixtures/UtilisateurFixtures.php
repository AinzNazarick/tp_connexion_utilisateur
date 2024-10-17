<?php

namespace App\DataFixtures;

use App\Entity\Utilisateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UtilisateurFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher){
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 5; $i++) {
            $user = new Utilisateur();
            $user->setEmail('user'.$i.'@example.com');
            $password = $this->hasher->hashPassword($user, '123456');
            $user->setPassword($password);
            $manager->persist($user);
        }

        for ($i = 0; $i < 2; $i++) {
            $admin = new Utilisateur();
            $admin->setEmail('admin' . $i . '@example.com');
            $password = $this->hasher->hashPassword($admin, '123456');
            $admin->setPassword($password);
            $admin->addRole('ROLE_ADMIN');
            $manager->persist($admin);
        }

        $superadmin = new Utilisateur();
        $superadmin->setEmail('superadmin@example.com');
        $password = $this->hasher->hashPassword($superadmin, '123456');
        $superadmin->setPassword($password);
        $superadmin->addRole('ROLE_SUPER_ADMIN');
        $manager->persist($superadmin);

        $manager->flush();
    }
}
