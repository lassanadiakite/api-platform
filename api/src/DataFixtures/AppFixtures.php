<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Card;
use App\Entity\User;
use App\Entity\Comment;
use App\Entity\Product;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{

 /*   private $encoder ;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
 */
    public function load(ObjectManager $manager)
    {

        $faker = Factory::create('FR-fr');

        $adminUser = new User();
        $adminUser->setFirstName('Lassana')
                  ->setLastName('Diakité')
                  ->setEmail('ld@gmail.com')
                  ->setPassword( '$argon2i$v=19$m=65536,t=4,p=1$Nzd4S2JTcHBDVVRCdmhaMQ$lnG+Lb9ZEAMoakhubfHGTRNWHun/QHgfZ0L9cbed7sI')
                //  ->setPicture('https://netrinoimages.s3.eu-west-2.amazonaws.com/2012/05/26/136161/57586/ac_cobra_shelby_cobra_3d_model_c4d_max_obj_fbx_ma_lwo_3ds_3dm_stl_506117_o.jpg')
                  ->setRoles(['ROLE_ADMIN']);

        $manager->persist($adminUser);
        
        
        //Nous gérons les utilisateurs (clients)
        $users =[];
        $genres = ['male', 'female'];

        for($i = 1; $i <=10; $i++){
            $user = new User();

            
         /*   $genre = $faker->randomElement($genres);

            $picture = 'https://randomuser.me/api/portraits/';
            $pictureId = $faker->numberBetween(1, 99) . '.jpg';

            $picture .= ($genre == 'male' ? 'men/' : 'women/') . $pictureId;
            */
            $password = ('password');
            
            $user->setFirstName($faker->firstname)
                 ->setLastName($faker->lastname)
                 ->setEmail($faker->email)
                 ->setRoles(['ROLE_USER'])
                 ->setPassword($password);
                // ->setPicture($picture);

            $manager->persist($user);
            $users []= $user;
        }


        // les vendeurs (seller)
            $usersSeller =[];
        for($j = 1; $j <=10; $j++){
            $userSeller = new User();

            
            $genre = $faker->randomElement($genres);

          /*  $picture = 'https://randomuser.me/api/portraits/';
            $pictureId = $faker->numberBetween(1, 99) . '.jpg';

            $picture .= ($genre == 'male' ? 'men/' : 'women/') . $pictureId;

            $hash = $this->encoder->encodePassword($userPresta, 'password');
          */
            $userSeller->setFirstName($faker->firstname)
            ->setLastName($faker->lastname)
            ->setEmail($faker->email)
            ->setRoles(['ROLE_SELLER'])
            ->setPassword("password");
           // ->setPicture($picture);
            $manager->persist($userSeller);
            $usersSeller[]= $userSeller;
        }

        //Nous gérons les catégories
        $categories = [];
        for($i=1; $i<=10; $i++){
            $category = new Category();
            $category->setName($faker->jobTitle($nbWords = 15, $variableNbWords = false));

            $manager->persist($category);
            $categories[]= $category;
        }
        // Gestion des Cards
            $cards = [];
        for ($i = 1; $i <= mt_rand(0, 10); $i++) {
            $card = new Card();
            $userCard   = $users[mt_rand(0, count($users) - 1)];

            $card->setUserId($userCard);
      
            $manager->persist($card);
            $cards[] = $card;
        }

        //Nous gérons les Produits
        for($i=1; $i <= 30; $i++){
        $product = new Product(); 
        

        $name       = $faker->jobTitle;
  //      $coverImage   = $faker->imageUrl(1000,350);
    //    $introduction = $faker->sentence($nbWords = 15, $variableNbWords = false);
        $description      = '<p>'. join('</p><p>', $faker->paragraphs(4)) . '</p>';
       /* $updatedAt    = $faker->dateTime('now');
        ->setUpdatedAt($updatedAt)*/
        
        $userSeller    = $usersSeller[mt_rand(0, count($usersSeller -1))];
        $category = $categories[mt_rand(0, count($categories) -1)];
        $card = $cards[mt_rand(0, count($cards) -1)];

        $product->setName($name)
          // ->setCoverImage($coverImage)
           ->setReference(1234) 
           ->setDescription($description)
           ->setPrice(mt_rand(40, 200))
           ->setUserId($userSeller)
           ->setCategory($category)
           ->addCard($card);
    
            // Gestion des commentaires
            if (mt_rand(0, 1)) {
                $comment = new Comment();
                $comment->setContent($faker->paragraph())
                    ->setRating(mt_rand(1, 5))
                    ->setAuthor($userCard)
                    ->setProduct($product);

                $manager->persist($comment);
            }
        
        $manager->persist($product);
        
        }
        $manager->flush();
    }
}
