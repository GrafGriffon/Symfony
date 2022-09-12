<?php

namespace App\Controller\pages;

use App\Entity\Category;
use App\Message\SmsNotification;
use App\Repository\CategoryRepository;
use Lcobucci\JWT\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\Recipient;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class IndexController extends AbstractController
{
    /**
     * @Route("/index/", name="index")
     */
    public function list(CategoryRepository $repository, NotifierInterface $notifier, MailerInterface $mailer, MessageBusInterface $bus): Response
    {
//        $notification = (new Notification('New Invoice', ['email']))
//            ->content('You got a new invoice for 15 EUR.');
//        $recipient = new Recipient(
//            'illiaa552@mail.ru',
//            '+375295585199'
//        );
//        $notifier->send($notification, $recipient);
        $bus->dispatch(new SmsNotification('Look! I created a message!'));

        try {
            return $this->render('index/index.html.twig', [
                'categories' => $repository->findAll(),
                'idParent' => 0
            ]);
        }
        catch (Throwable $e){
            return $this->render('error.html.twig');
        }
    }
}