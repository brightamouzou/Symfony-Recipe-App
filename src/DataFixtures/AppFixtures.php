<?php

namespace App\DataFixtures;

use App\Entity\Contact;
use App\Entity\Ingredient;
use App\Entity\Mark;
use App\Entity\Receipt;
use App\Entity\User;
use Doctrine\Bun;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private Generator $faker;
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->faker=Factory::create("fr_FR");
        $this->hasher=$hasher;
    }

    public function load(ObjectManager $manager): void
    {   

        $ingredients=[];
        $users = [];
        $receipts = [];
        $marks=[];


        $admin=new User();

        $admin->setEmail('admin@symfreceipt.com');
        $admin->setFullName('Administrateur Symf-Receipt');
        $admin->setRoles(["ROLE_USER", "ROLE_ADMIN"]);
        $admin->setPlainPassword("admin");

        $manager->persist($admin);
        $users[]=$admin;

        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setFullName($this->faker->name());
            $user->setPseudo(mt_rand(0, 1) == 0 ? $this->faker->firstName() : null);
            $user->setEmail($this->faker->email());
            $user->setPlainPassword('password');
            $users[]=$user;
            
            // $hashedPassword= $this->hasher->hashPassword(
            //     $user, 
            //     "password"
            // );

            // $user->setPassword($hashedPassword);

            $manager->persist($user);
        }

        for($i=0; $i<50; $i++){
            $ingredient = new Ingredient();
            $ingredient->setName($this->faker->word());
            $ingredient->setPrice(mt_rand(0,100));
            $ingredient->setUser($users[mt_rand(0,count($users)-1)]);
            array_push($ingredients,$ingredient);
            $manager->persist($ingredient);
        }

        for($i=0; $i<50; $i++){
            $receipt=new Receipt();
            $receipt->setName($this->faker->word());
            $receipt->setDifficulty(mt_rand(1,5));
            $receipt->setDuration(mt_rand(0, 1) == 0 ? mt_rand(1,1440):null);
            $receipt->setNbPersons(mt_rand(0, 1) == 0 ? mt_rand(1,50):null);
            $receipt->setDescription($this->faker->text());
            $receipt->setPrice(mt_rand(0, 1) == 0 ?$this->faker->randomFloat()%1000:null);
            $receipt->setIsPublic(mt_rand(0, 1) == 0 ?0:1);
            $receipt->setUser($users[mt_rand(0, count($users) - 1)]);

            $receipts[]=$receipt;
            for ($k=0; $k < mt_rand(5,15); $k++) { 
                $receipt->addIngredient($ingredients[mt_rand(0,count($ingredients)-1)]);
                # code...
            }
            $manager->persist($receipt);
        }

        foreach($receipts as  $receipt){
            for($i=0; $i<mt_rand(0,5);$i++ ){
                $mark=new Mark();
                $mark->setUser($users[mt_rand(0,5)]);
                $mark->setValue(mt_rand(1, 5));
                $mark->setReceipt($receipt);
                $marks[]=$mark;
                $manager->persist($mark);
            }
        }

        
        for ($i=0; $i<50; $i++){
            $contact=new Contact();
            $contact->setFullName(mt_rand(0,1)==0?$this->faker->name():null);
            $contact->setSubject($this->faker->title());
            $contact->setEmail($this->faker->email());
            $contact->setMessage($this->faker->text(250));

            $manager->persist($contact);
        }

        // for ($i=0; $i <50 ; $i++) { 
        //     $mark=new Mark();
        //     $mark->setValue(mt_rand(0,5));
        //     $mark->setUser($users[mt_rand(0,5)]);
        //     $mark->setReceipt($receipts[mt_rand(0, 5)]);
        //     # code...
        // }
        $manager->flush();
    }
}
