<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
    	$faker = Factory::create('fr_FR');
    	$categories = [
    		1 => "Informatique et Bureaux",
		    2 => "Jouets, Enfants et Bébés",
		    3 => "Cuisines & Maison",
		    4 => "Bricolage, Jardin & Animalerie",
		    5 => "Vêtements, Chaussures, Bijoux",
		    6 => "Sports et Loisirs",
		    7 => "Automobile et Industrie",
		    8 => "Beauté, Santé et Bien-être",
		    9 => "Épicerie, Boissons et Entretien",
		    10 => "Autres"
	    ];

    	for ($i=1; $i<=10; $i++) {
    		$category = (new Category())->setName($categories[$i]);
		    $manager->persist($category);
	    }

        $manager->flush();
    }
}
