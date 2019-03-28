<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\MessageType;
use DateTime;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/", name="contact")
     */
    public function index(ObjectManager $manager, Request $request,\Swift_Mailer $mailer)
    {
        $user = new User();
        $form = $this->createForm(MessageType::class,$user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            // Swift_mailer message configuration
            $date = new DateTime();
            $emails = $user->getDestination()->getEmails();
            $message = (new \Swift_Message('Contact'))
                ->setFrom($user->getEmail())
                ->setTo($emails)
                ->setBody(
                    $this->renderView(
                        'emails/contactBody.html.twig',
                        [
                            'name' => $user->getName(),
                            'surname' => $user->getSurname(),
                            'message' => $user->getMessage(),
                            'created_at'=> $date->format("g:ia l jS F Y")
                        ]
                    ),
                    'text/html'
                );
            //send the email
            $mailer->send($message);
            //Save message to db
            $manager->persist($user);
            $manager->flush();
            //Flash message
            $this->addFlash(
                'notice',
                (!is_array($emails))?'Message sent successfully to '.$emails.' !':
                    'Message sent successfully to '.$emails[0].', '.$emails[1].' !');
        }
   return $this->render('contact/index.html.twig',
       [
           'contactForm' => $form->createView()
       ]);
    }
}
