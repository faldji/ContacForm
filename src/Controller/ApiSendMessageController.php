<?php

namespace App\Controller;

use App\Entity\Department;
use App\Entity\User;
use App\Form\MessageType;
use App\Service\MessageSender;
use Doctrine\Common\Persistence\ObjectManager;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiSendMessageController extends AbstractFOSRestController
{
    /**
     * @SWG\Response(
     *     response=201,
     *     description="Message sent successfuly",
     *     @SWG\Schema(@SWG\Items(ref=@Model(type=MessageType::class)))
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Form validation error"
     * )
     * @SWG\Response(
     *     response=500,
     *     description="Input error"
     * )
     * @SWG\Parameter(
     *     name="name",
     *     in="formData",
     *     required= true,
     *     type="string",
     *     description="Name."
     * )
     *  @SWG\Parameter(
     *     name="surname",
     *     in="formData",
     *     type="string",
     *     description="Surname"
     * )
     * @SWG\Parameter(
     *     name="email",
     *     required= true,
     *     in="formData",
     *     type="string",
     *     description="From."
     * )
     * @SWG\Parameter(
     *     name="destination",
     *     required= true,
     *     in="formData",
     *     type="integer",
     *     description="To."
     * )
     * @SWG\Parameter(
     *     name="message",
     *     required= true,
     *     in="formData",
     *     type="string",
     *     description="Message Content."
     * )
     * @SWG\Tag(name="Message Sender")
     * @Rest\Post("/api/contact", name="post_api_contact")
     * @Rest\View()
     */
    public function putSendMessageAction(ObjectManager $manager, Request $request, MessageSender $sender)
    {
        $user = new User();
        $form = $this->createForm(MessageType::class, $user);
        $form->submit($request->request->all());
        if ($form->isSubmitted() && !$form->isValid()) {
            return $this->handleView(
                $this->view($form)
            );
        }
        $user = $form->getData();
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
        $manager->persist($user);
        $manager->flush();

        return $this->handleView(
            $this->view(
                [
                    'status' => 'Success',
                ],
                Response::HTTP_CREATED
            )
        );
    }
}
