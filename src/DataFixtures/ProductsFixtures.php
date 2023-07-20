<?php

namespace App\DataFixtures;

use App\Entity\Products;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Monolog\DateTimeImmutable;
use Faker;

class ProductsFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        for($prd = 1; $prd <= 10; $prd++) {
            $product = new Products();
            $product -> setName($faker->text(15));
            $product -> setDescription($faker->text(15));
            $product -> setPrice($faker->numberBetween(900, 150000));   //en cents
            $product -> setStock($faker->numberBetween(0, 10));

            // on va rechercher le numéro de catégorie qui était stocké dans CategoriesFixtures
            $category = $this->getReference('cat-'.rand(1,12));
            $product->setCategories($category);
            $product->setCreatedAt(new DateTimeImmutable('Y-m-d H:i:s'));

            $this->setReference('prd-'.$prd, $product);
            $manager->persist($product);

        }

        $manager->flush();
    }
}
