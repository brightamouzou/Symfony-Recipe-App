<?php 
    namespace App\EntityListener;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

    class UserListener{

        private UserPasswordHasherInterface $hasher;
        public function __construct(UserPasswordHasherInterface $hasher)
        {
            $this->hasher=$hasher;   
        }

        public function prePersist(User $user){

            $this->encodePassword($user);
        }

        public function preUpdate(User $user)
        {
            $this->encodePassword($user);

        }

        public function encodePassword(User $user){

            // if($user->getPassword()!==null){
            //     $user->setPlainPassword($user->getPassword());
            // }

            if(!$user->getPlainPassword()){
                return;
            }

            $hashedPassword=$this->hasher->hashPassword(
                $user,
                $user->getPlainPassword()
            );

            $user->setPassword($hashedPassword);
            $user->setPassword($hashedPassword);

        }
        
    }

?>