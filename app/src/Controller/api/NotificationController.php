<?php

namespace App\Controller\api;

use App\Entity\Notification;
use App\Handler\SendMailHandler;
use App\Repository\CategoryRepository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\Recipient;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/api")
 */
class NotificationController extends AbstractController
{
    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param CategoryRepository $repository
     * @return JsonResponse
     * @throws \Exception
     * @Route("/notification", name="notification", methods={"POST"})
     */
    public function addMessage(Request $request, EntityManagerInterface $entityManager, SendMailHandler $handler, UserRepository $repository)
    {

        try {
            $request = $this->transformJsonBody($request);
            $counter = 0;
            $notification = (new Notification())
                ->setTitle($request->get('title'))
                ->setDateSend(new DateTime())
                ->setDescription($request->get('description'));
            foreach ($request->get('users') as $user) {
                if ($repository->findOneBy(['email' => $user])) {
                    $notification->addUser($repository->findOneBy(['email' => $user]));
                    $counter++;
                    $handler->sendMail($user, $notification->getTitle(), $notification->getDescription());
                }
            }
            if ($counter > 0) {
                $entityManager->persist($notification);
                $entityManager->flush();
            }

            $data = [
                'status' => 200,
                'success' => "Post added successfully",
            ];
            return new JsonResponse($data);

        } catch (\Exception $e) {
            $data = [
                'status' => 422,
                'errors' => "Data no valid",
            ];
            return new JsonResponse($data, 422);

        }
    }

    protected function transformJsonBody(\Symfony\Component\HttpFoundation\Request $request)
    {
        $data = json_decode($request->getContent(), true);

        if ($data === null) {
            return $request;
        }

        $request->request->replace($data);

        return $request;
    }
}