<?php

namespace App\Controller\forms;

use App\Entity\Notification;
use App\Entity\Products;
use App\Form\NotificationForm;
use App\Handler\AddCountHistoryHandler;
use App\Handler\AddPriceHistoryHandler;
use App\Form\ProductForm;
use App\Handler\SendMailHandler;
use App\Repository\CategoryRepository;
use App\Repository\ProductsRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\Recipient;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class NotificationController extends AbstractController
{

    /**
     * @Route("notification", methods={"GET"})
     */
    public function addProductGetForm(CategoryRepository $repository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(NotificationForm::class);
        $form->handleRequest($request);
        return $this->render('formsadding/addNotification.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("notification", methods={"POST"})
     */
    public function addProductPostForm(CategoryRepository $repository, SendMailHandler $handler, Request $request, EntityManagerInterface $entityManager): Response
    {
        $product = new Notification();
        $form = $this->createForm(NotificationForm::class, $product);
        $form->handleRequest($request);
        $productForm = $form->getData();//$request->request->all();
        $productForm->setDateSend(new DateTime());
        $entityManager->persist($productForm);
        foreach ($productForm->getUser() as $user) {
            $handler->sendMail($user->getEmail(), $productForm->getTitle(), $productForm->getDescription());
        }
        $entityManager->flush();
        if ($form->isSubmitted() && $form->isValid()) {
            return $this->render('index/index.html.twig', [
                'categories' => $repository->findAll(),
                'idParent' => 0
            ]);
        }
        return $this->render('formsadding/addProduct.html.twig', array(
            'form' => $form->createView()
        ));
    }
}