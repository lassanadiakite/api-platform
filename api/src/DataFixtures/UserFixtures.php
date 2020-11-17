<?php

namespace App\DataFixtures;

use App\Entity\Card;
use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
	    $faker = Factory::create('fr_FR');
	    $categories = $manager->getRepository(Category::class)->findAll();

	    for ($i=0; $i<15; $i++) {

	    	// User
	    	$user = (new User())
			    ->setFirstName($faker->firstName)
			    ->setLastName($faker->lastName)
			    ->setEmail($faker->email)
			    ->setPassword('password')
			    ->setRoles(["ROLE_USER"])
		    ;

		    // Card
		    $card = (new Card())
			    ->setUserId($user);

	    	for ($j=0; $j<5; $j++) {

	    		// Product
	    		$product = (new Product())
				    ->setName($faker->name)
				    ->setPrice($faker->numberBetween(10, 500))
				    ->setReference($faker->vat)
				    ->setDescription($faker->paragraph(5))
				    ->setCategory($faker->randomElement($categories))
				    ->setUserId($user)
			    ;

	    		// Comment
			    for ($k=0; $k<4; $k++) {
				    $comment = (new Comment())
					    ->setIdUser($user)
					    ->setContent($faker->sentence(20))
					    ->setProduct($product)
					    ->setRating($faker->randomElement([1, 2, 3, 4, 5]))
				    ;
				    $manager->persist($comment);
			    }

			    // Price
			    $price = 0;

			    for ($l=0; $l<10; $l++) {
			    	$price = $price + $product->getPrice();
			    	$card
					    ->addProduct($product)
					    ->setPrice($price);
			    }

			    $manager->persist($card);
	    		$manager->persist($product);
		    }
	    }

        $manager->flush();
    }

	public function getDependencies() {
		return [
			CategoryFixtures::class
		];
	}
}
