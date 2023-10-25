<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Service\FlashMessageHelper;
use App\Service\UserManagerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/registration', name: 'registration', methods: ['GET', 'POST'])]
    public function registration(Request $request, UserManagerInterface $userManager, EntityManagerInterface $entityManager, FlashMessageHelper $flashHelper): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user,[
           'method' => 'POST',
            'action' => $this->generateUrl('registration')
        ]);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $password = $form["plainPassword"]->getData();
            $userManager->processNewUser($user, $password);
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash("succes", "Inscription réussi!");
            return $this->redirectToRoute('shop');
        }
        $flashHelper->addFormErrorsAsFlash($form);
        return $this->render('user/registration.html.twig', ['form' => $form]);
    }
}
