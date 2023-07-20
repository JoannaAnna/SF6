<?php

namespace App\DataFixtures;

use App\Entity\Categories;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoriesFixtures extends Fixture
{
    private $counter = 1;
    public function load(ObjectManager $manager): void
    {
        $parent = $this-> createCategory('Informatique', null, $manager); // c'est un parent donc deuxieme valeur est null

        $this -> createCategory('Ordinateurs portable', $parent, $manager);
        $this -> createCategory('Ecrans', $parent, $manager);
        $this -> createCategory('Souris', $parent, $manager);

        $parent = $this-> createCategory('Mode', null, $manager);

        $this -> createCategory('Femme', $parent, $manager);
        $this -> createCategory('Enfant', $parent, $manager);
        $this -> createCategory('Homme', $parent, $manager);

        $parent = $this-> createCategory('Maison', null, $manager);

        $this -> createCategory('Meubles', $parent, $manager);
        $this -> createCategory('Textiles', $parent, $manager);
        $this -> createCategory('Décorations', $parent, $manager);

        $manager->flush();
    }

    public function createCategory(string $name, Categories $parent = null, ObjectManager $manager) {
        $category = new Categories();
        $category -> setName($name);
        $category -> setParent($parent);
        $manager -> persist($category);

        $this->addReference('cat-'.$this->counter, $category);   // donc cat-1, cat-2...
        $this->counter++;
        
        return $category;
    }
}
