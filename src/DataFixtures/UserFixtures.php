<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }


    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        // Création d’un utilisateur de type “contributeur” (= auteur)
        $user = new User();
        $user->setUsername($faker->userName());
        $user->setEmail('contributor@monsite.com');
        $user->setRoles(['ROLE_CONTRIBUTOR']);
        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            'user1234'
        );

        $user->setPassword($hashedPassword);
        $manager->persist($user);

        // Création d’un utilisateur de type “administrateur”
        $admin = new User();
        $admin->setUsername($faker->userName());
        $admin->setEmail('admin@monsite.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $hashedPassword = $this->passwordHasher->hashPassword(
            $admin,
            'adminpassword'
        );
        $admin->setPassword($hashedPassword);
        $manager->persist($admin);

        $faker = Factory::create();
        for($i = 0; $i < 20; $i++) {
            $user = new User();
            $user->setUsername($faker->userName());
            $user->setEmail($faker->email());
            $user->setRoles(['ROLE_CONTRIBUTOR']);
            $hashedPassword = $this->passwordHasher->hashPassword(
                $user,
                'userpassword'
            );
            $user->setPassword($hashedPassword);
            $manager->persist($user);
            $this->addReference('owner_' . $i, $user);
        }

        $manager->flush();
    }
}

    