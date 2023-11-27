<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class ShoppingCartController extends AbstractController
{

    public function __construct(
        private RequestStack $requestStack,
    ) {
        // Accessing the session in the constructor is *NOT* recommended, since
        // it might not be accessible yet or lead to unwanted side-effects
        // $this->session = $requestStack->getSession();
    }


    /**
     * Ajouter un article au panier.
     *
     * @param int $idProduct
     * @return Response
     */

    #[Route('/product/{idProduct}/addToShoppingCart', name: 'addToShoppingCart')]
    public function addToShoppingCart(int $idProduct): Response
    {
        // Récupérer le panier actuel depuis la session
        $shoppingCart = $this->requestStack->getSession()->get('shoppingCart', []);

        // Ajouter l'ID de l'article au panier
        $shoppingCart[] = $idProduct;

        // Enregistrer le panier mis à jour dans la session
        $this->requestStack->getSession()->set('shoppingCart', $shoppingCart);

        return $this->redirectToRoute('shop'); // Rediriger vers la page d'accueil (ou une autre page)
    }

    /**
     * Afficher le contenu du panier.
     *
     * @return Response
     */
    public function showShoppingCart(): Response
    {
        // Récupérer le panier depuis la session
        $shoppingCart = $this->requestStack->getSession()->get('shoppingCart', []);

        // Vous pouvez maintenant utiliser $panier comme bon vous semble

        return $this->render('shop/shoppingCart.html.twig', [
            'shoppingCart' => $shoppingCart,
        ]);
    }

}
