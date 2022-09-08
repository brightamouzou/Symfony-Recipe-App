<?php

namespace App\Controller;

use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Entity\User;

class SecurityController extends AbstractController
{
    /**
     * @Route("/connexion", name="security.login", methods={"GET", "POST"})
     */
public function login(AuthenticationUtils $authenticationUtils): Response
    {
        return $this->render('pages/security/login.html.twig', [
            'last_username' => $authenticationUtils->getLastUsername(),
            'error' => $authenticationUtils->getLastAuthenticationError(),
        ]);
    }

    /**
     * Route de deconnexion
     * @Route("/deconnexion", name="security.logout", methods={"GET", "POST"})
     * @return void
     */
    public function logout() {

    }


    /**
     * Route d'inscription
     * @Route("/registration", name="security.registration", methods={"GET", "POST"})
     * @return void
     */
    public function registration(Request $request, EntityManagerInterface $manager){
        $user=new User();
        $user->setRoles(['ROLE_USER']);
        $form=$this->createForm(RegistrationType::class,$user);

        $form->handleRequest($request);
        

        // dd($form->getData());
        if($form->isSubmitted() && $form->isValid()){
            // $user->setPlainPassword()
            $user=$form->getData();
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "Votre compte a bien été  créee"
            );

            return $this->redirectToRoute("security.login");
        }
        return $this->render(
            "pages/security/registration.html.twig",
            [
                'form'=>$form->createView()
            ]
        );
    }
}
