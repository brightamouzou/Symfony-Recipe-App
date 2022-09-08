<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserPasswordType;
use App\Form\UserType;
use App\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     */
    /**
     * Modification des informations personnelles
     * @Route("/user/edit/{id}", name="user.edit", methods={"GET","POST"})
     * @param integer $id
     * @param UserRepository $repository
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function edit(int $id, UserRepository $repository,Request $request, EntityManagerInterface $manager): Response
    {
        $user = new User();
        $user = $repository->findOneBy(['id' => $id]);

        if(!$this->getUser()){

           return  $this->redirectToRoute("security.login");
        }

        if($this->getUser()!==$user){
            $this->addFlash(
                'danger',
                "Vous n'etes pas autorisé à effectuer cette opérarion"
            );
            return $this->redirectToRoute("receipt.index");
        }
       
        $form=$this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $user=$form->getData();
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                "success",
                "Information modifiées avec succès"
            );
        }

        return $this->render('pages/user/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Editer le mot de passe
     * @Route("/user/edit-password/{id}",name="user.edit.password",methods={"GET", "POST"})
     * @param integer $id
     * @return Response
     */
    public function editPassword(int $id,UserRepository $repository,Request $request,EntityManagerInterface $manager,UserPasswordHasherInterface $hasher):Response{
        $user=new User();
        $user=$repository->findOneBy(['id'=>$id]);
        $form=$this->createForm(UserPasswordType::class);
        $form->handleRequest($request);

        if (!$this->getUser()) {
            return $this->redirectToRoute("security.login");
        }

        if ($this->getUser() !== $user) {
            $this->addFlash(
                'danger',
                "Vous n'etes pas autorisé à effectuer cette opérarion"
            );
            return $this->redirectToRoute("receipt.index");
        }

        if($form->isSubmitted() && $form->isValid()){
            $formData= $form->getData();
            if($hasher->isPasswordValid($user, $formData["plainPassword"])) {

                // dd($formData);

                $user->setUpdatedAt(new DateTimeImmutable());
                $user->setPlainPassword($formData["newPassword"]);
                $manager->persist($user); //Normalement , il devrait declencher la methode preUpdate de l'entityListener mais apparemment c'est un bug au niveau de Symfony meme 
                $manager->flush();
                
                $this->addFlash('success', "Mot de passe modifié avec succès");

            }else{
                $this->addFlash('danger', "Mot de passe invalide");
            }
        }

        // $manager=$manager->find
        return $this->render("pages/user/edit_password.html.twig",[
            'form'=>$form->createView()
        ]);
    }
}
