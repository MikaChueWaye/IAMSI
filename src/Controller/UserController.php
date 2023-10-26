<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\User;
use App\Form\UserType;
use App\Service\FlashMessageHelper;
use App\Service\UserManagerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends AbstractController
{
    #[isGranted('ROLE_USER')]
    #[Route('/user/inventory', name: 'userInventory')]
    public function userInventory(): Response
    {
        return $this->render('user/inventory.html.twig');
    }

    #[Route('/registration', name: 'registration', methods: ['GET', 'POST'])]
    public function registration(Request $request, UserManagerInterface $userManager, EntityManagerInterface $entityManager, FlashMessageHelper $flashHelper): Response
    {
        if($this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('shop');
        }

        $user = new User();
        $form = $this->createForm(UserType::class, $user, [
            'method' => 'POST',
            'action' => $this->generateUrl('registration')
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $password = $form["plainPassword"]->getData();
            $userManager->processNewUser($user, $password);
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash("success", "Inscription réussi!");
            return $this->redirectToRoute('shop');
        }
        $flashHelper->addFormErrorsAsFlash($form);
        return $this->render('user/registration.html.twig', ['form' => $form]);
    }

    #[Route('/connection', name: 'connection',  methods: ['GET', 'POST'])]
    public function connexion(AuthenticationUtils $authenticationUtils) : Response {
        if($this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('shop');
        }
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('user/connection.html.twig', ["lastUserName" => $lastUsername]);
    }

    #[isGranted('ROLE_USER')]
    #[Route('/deconnection', name: 'deconnection', methods: ['POST'])]
    public function deconnection(): never
    {
        throw new \Exception("Cette route n'est pas censée être appelée. Vérifiez security.yaml");
    }
}
