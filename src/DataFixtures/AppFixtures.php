<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        for($i=0;$i<50;$i++){
            $product = new Product();
            $product->setPriceHt(mt_rand(10, 100));
            $product->setName($faker->title);
            $product->setDescription($faker->title);
            $manager->persist($product);
        }
        $manager->flush();
    }
}
