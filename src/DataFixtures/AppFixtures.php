<?php

namespace App\DataFixtures;

use App\Entity\Address;
use App\Entity\Gender;
use App\Entity\User;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;

    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');


            for ($i=0;$i<3;$i++){
                $address = new Address();
                $address->getUser($faker->numberBetween(14, 56));
                $address->setNumber($faker->numberBetween(1, 299));
                $address->setStreet($faker->streetName);
                $address->setPostalCode($faker->postcode);
                $address->setCity($faker->city);
                $manager->persist($address);
        }
        $manager->flush();
    }
}
