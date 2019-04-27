<?php
namespace App\Controller;

use App\Entity\User;
use App\Form\MessageType;
use App\Service\MessageSender;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/", name="contact")
     */
    public function index(ObjectManager $manager, Request $request, MessageSender $sender)
    {
        $user = new User();
        $form = $this->createForm(MessageType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $name = $user->getName().' '.$user->getSurname();
            $emails = $user->getDestination()->getEmails();
            $body =
                $this->renderView(
                    'emails/contactBody.html.twig',
                    [
                        'name' => $name,
                        'message' => $user->getMessage()
                    ]
                );

            //Send email
            $sender->sendEmail($user->getEmail(), $emails, $body);
            //Flash message
            $this->addFlash('notice', 'Message sent successfully');
            //Save message to db
            $manager->persist($user);
            $manager->flush();
        }
        return $this->render('contact/index.html.twig', ['contactForm' => $form->createView()]);
    }
}
