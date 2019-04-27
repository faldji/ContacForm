<?php


namespace App\Service;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

class MessageSender
{
    private $mailer;
    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }
    /** Swift_mailer message configuration
     * @param string $from
     * @param string|array $to
     * @param string $name
     * @param string $content
     * @param \Swift_Mailer $mailer
     */
    public function sendEmail($from, $to, $body)
    {
        $message = (new \Swift_Message('Contact'))
            ->setFrom($from)
            ->setTo($to)
            ->setBody($body, 'text/html');
        //send the email
        $this->mailer->send($message);
    }
}
