<?php
namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Movie;
use http\Env;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;

final class MovieMailSubscriber implements EventSubscriberInterface
{
    private $mailer;
    private $skipSendEmail;
    private $emailFrom;

    public function __construct(MailerInterface $mailer, $emailFrom, $skipSendEmail)
    {
        $this->mailer = $mailer;
        $this->skipSendEmail = $skipSendEmail;
        $this->emailFrom = $emailFrom;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['sendMail', EventPriorities::POST_WRITE],
        ];
    }

    public function sendMail(ViewEvent $event): void
    {
        if ('true' === $this->skipSendEmail){
            return;
        }

        $movie = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$movie instanceof Movie || Request::METHOD_POST !== $method) {
            return;
        }

        $message = (new Email())
            ->from(urldecode($this->emailFrom))
            ->to($movie->getOwner()->getEmail())
            ->subject("A new movie '{$$movie->getName()}' has been created")
            ->text(sprintf('The movie "(#%d) #%s" has been added.', $movie->getId(), $movie->getName()));

        $this->mailer->send($message);
    }
}
