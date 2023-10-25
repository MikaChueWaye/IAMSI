<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShopController extends AbstractController
{
    #[Route('/shop', name: 'shop')]
    public function shop(): Response
    {
        return $this->render('shop/shop.html.twig');
    }

    #[Route('/shoppingCart', name: 'shoppingCart')]
    public function shoppingCart(): Response
    {
        return $this->render('shop/shoppingCart.html.twig');
    }
}
