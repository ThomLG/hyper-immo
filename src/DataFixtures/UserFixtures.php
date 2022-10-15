<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker;
use Faker\Factory;

class UserFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $passwordEncoder
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $admin = new User();;
        $admin->setEmail('admin@mail.com');
        $admin->setLastName('Dubois');
        $admin->setFirstName('Martin');
        $admin->setAvatar('mdubois');
        $admin->setPassword($this->passwordEncoder->hashPassword($admin, 'jml650')); // je met l'user et son mdp
        $admin->setRoles(['ROLE_ADMIN']);

        $manager->persist($admin);

        $faker = Faker\Factory::create('fr_FR');

        for ($u = 1; $u <= 5; $u++) { // j'icrémente pour créer 5 nouveaux users. 
            $user = new User();;
            $user->setEmail($faker->email);
            $user->setLastName($faker->lastName);
            $user->setFirstName($faker->firstName);
            $user->setAvatar($faker-> userName);
            $user->setPassword(
                $this->passwordEncoder->hashPassword($user, 'secret')
            ); // je met l'user et son mdp
            dump($user);
            $manager->persist($user);
        }

        $manager->flush();
    }
}
