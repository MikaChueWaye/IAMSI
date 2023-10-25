<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/user', name: 'user')]
    public function user(): Response
    {
        return $this->render('user/user.html.twig');
    }

    #[Route('/user/{id}/inventory', name: 'userInventory')]
    public function userInventory(): Response
    {
        return $this->render('user/inventory.html.twig');
    }
}
