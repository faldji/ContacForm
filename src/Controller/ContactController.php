<?php

namespace App\Controller;

use App\Entity\Department;
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
        $form = $this->createForm(MessageType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){

            //Manually set the user data from Post request
            $data=$request->request->get('message');
            $user->setName($data['name']);
            $user->setSurname($data['surname']);
            $user->setEmail($data['email']);
            $user->setDestination($manager->find(Department::class,$data['destination']));
            $user->setMessage($data['message']);
            $date = new DateTime();
            $emails = ($user->getDestination()->getEmail2() != null)?
                [$user->getDestination()->getEmail1(),$user->getDestination()->getEmail2()]:$user->getDestination()->getEmail1();

            // Swift_mailer message configuration
            $message = (new \Swift_Message('Contact'))
                ->setFrom($user->getEmail())
                ->setTo($emails)
                ->setBody(
                    $this->renderView(
                        'emails/contactBody.html.twig',
                        ['name' => $user->getName(),'surname'=>$user->getSurname(),
                            'message'=>$user->getMessage(),'created_at'=> $date->format("g:ia l jS F Y")]
                    ),
                    'text/html'
                );
            $mailer->send($message);
            $manager->persist($user);
            $manager->flush();

            //Flash message
            $this->addFlash(
                'notice',
                (!is_array($emails))?  'Message sent successfully to '.$emails.' !' :
                    'Message sent successfully to '.$emails[0].', '.$emails[1].' !');
        }
   return $this->render('contact/index.html.twig',['contactForm'=>$form->createView()]);
    }
}
