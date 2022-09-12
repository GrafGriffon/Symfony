<?php

namespace App\Handler;

use App\Entity\Products;
use App\Entity\User;
use App\Repository\CategoryRepository;
use App\Repository\ProductsRepository;
use App\Repository\SupplyRepository;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\Recipient;
use Symfony\Component\Security\Core\User\UserInterface;

class SendMailHandler
{
    private $notifier;

    public function __construct(NotifierInterface $notifier)
    {
        $this->notifier = $notifier;
    }

    public function sendMail($mail, $title, $description): void
    {
        $notificationMail = (new \Symfony\Component\Notifier\Notification\Notification($title, ['email']))
            ->content($description);
        $recipient = new Recipient($mail);
        $this->notifier->send($notificationMail, $recipient);
    }
}