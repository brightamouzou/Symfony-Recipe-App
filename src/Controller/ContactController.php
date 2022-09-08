<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Service\MailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact.index", methods={"GET","POST"})
     */
    public function index(
        Request $request, 
        EntityManagerInterface $manager,
        MailService $mailService
    ): Response
    {
        $contact=new Contact();
          if($this->getUser()){
            // dd($this->getUser());
            $contact->setFullName($this->getUser()->getFullName());
            $contact->setEmail($this->getUser()->getEmail());
        }


        $form=$this->createForm(ContactType::class, $contact);
        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $contact = $form->getData();

          

            $mailService->sendTemplatedMail(
                $contact->getEmail(),
                $contact->getSubject(),
                'emails/contact.html.twig',
                $contact
            );

            $manager->persist($contact);
            $manager->flush();

            $this->addFlash("success", "Message envoyé avec succès");
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
