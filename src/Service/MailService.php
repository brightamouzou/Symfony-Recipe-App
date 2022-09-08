<?php 

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailService{
        private $mailer;
        public function __construct(MailerInterface $mailer)
        {
         $this->mailer=$mailer;   
        }

        public function sendPlainMail(
            $from, 
            $subject, 
            $message, 
            $to= "admin@symf-receipt.com"
            
        ):void{

            $email = (new Email())

                ->from($from)
                ->to($to)
                //->cc('cc@example.com')
                //->bcc('bcc@example.com')
                //->replyTo('fabien@example.com')
                //->priority(Email::PRIORITY_HIGH)
                ->subject($subject)
                ->html('<p>'.$message.'</p>');

            $this->mailer->send($email);
            
        }


    public function sendTemplatedMail(
        string $from,
        string $subject,
        string $templatePath,
        $context,
        $to = "admin@symf-receipt.com"

    ): void {

      

        $email = (new TemplatedEmail())

            ->from($from)
            ->to($to)
            ->subject($subject)
            // path of the Twig template to render
            ->htmlTemplate($templatePath)
            // pass variables (name => value) to the template
            ->context([
                'emailInfos' =>$context 
            ]);

        $this->mailer->send($email);
    }
        
}
    
?>