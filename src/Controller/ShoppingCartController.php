<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class ShoppingCartController extends AbstractController
{




    /**
     * Ajouter un article au panier.
     *
     * @param int $idProduct
     * @return Response
     */

    #[Route('/product/{idProduct}/addToShoppingCart', name: 'addToShoppingCart')]
    public function addToShoppingCart(int $idProduct, Request $request): Response
    {
        // Récupérer le panier actuel depuis la session
        $shoppingCart = $request->getSession()->get('shoppingCart', []);

        // Ajouter l'ID de l'article au panier
        if(!empty($shoppingCart[$idProduct])){
            $shoppingCart[$idProduct]++;
        }
        else{
            $shoppingCart[$idProduct] = 1;
        }

        // Enregistrer le panier mis à jour dans la session
        $request->getSession()->set('shoppingCart', $shoppingCart);

        dd($request->getSession()->get('shoppingCart'));
        //return $this->redirectToRoute('shop'); // Rediriger vers la page d'accueil (ou une autre page)
    }

    #[Route('/product/{idProduct}/removeToShoppingCart', name: 'removeToShoppingCart')]
    public function removeToShoppingCart(int $idProduct, Request $request): Response
    {
        // Récupérer le panier actuel depuis la session
        $shoppingCart = $request->getSession()->get('shoppingCart', []);

        // Ajouter l'ID de l'article au panier
        if(!empty($shoppingCart[$idProduct])){
            $shoppingCart[$idProduct]--;
        }

        // Enregistrer le panier mis à jour dans la session
        $request->getSession()->set('shoppingCart', $shoppingCart);

        dd($request->getSession()->get('shoppingCart'));
        //return $this->redirectToRoute('shop'); // Rediriger vers la page d'accueil (ou une autre page)
    }

    /**
     * Afficher le contenu du panier.
     *
     * @return Response
     */
    public function showShoppingCart(Request $request): Response
    {
        // Récupérer le panier depuis la session
        $shoppingCart = $request->getSession()->get('shoppingCart', []);

        // Vous pouvez maintenant utiliser $panier comme bon vous semble

        return $this->render('shop/shoppingCart.html.twig', [
            'shoppingCart' => $shoppingCart,
        ]);
    }

}
