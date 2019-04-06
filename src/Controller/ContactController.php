<?php
namespace App\Controller;

use App\Entity\User;
use App\Form\MessageType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/", name="contact")
     */
    public function index(ObjectManager $manager, Request $request, \Swift_Mailer $mailer)
    {
        $user = new User();
        $form = $this->createForm(MessageType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $name = $user->getName().' '.$user->getSurname();
            $emails = $user->getDestination()->getEmails();
            //Send email
            $this->sendEmail($user->getEmail(), $emails, $name, $user->getMessage(), $mailer);
            //Save message to db
            $manager->persist($user);
            $manager->flush();
        }
        return $this->render('contact/index.html.twig', ['contactForm' => $form->createView()]);
    }

    /** Swift_mailer message configuration
     * @param string $from
     * @param string|array $to
     * @param string $name
     * @param string $content
     * @param \Swift_Mailer $mailer
     */
    public function sendEmail($from, $to, $name, $content, \Swift_Mailer $mailer)
    {
        $message = (new \Swift_Message('Contact'))
            ->setFrom($from)
            ->setTo($to)
            ->setBody(
                $this->renderView(
                    'emails/contactBody.html.twig',
                    [
                        'name' => $name,
                        'message' => $content
                    ]
                ),
                'text/html'
            );
        //send the email
        $mailer->send($message);
        //Flash message
        $this->addFlash('notice', 'Message sent successfully');
    }
}
