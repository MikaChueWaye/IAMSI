<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Service\FlashMessageHelperInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
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
    public function addToShoppingCart(int $idProduct, RequestStack $session, ProductRepository $repository, FlashMessageHelperInterface $flashMessageHelper): Response
    {
        // Récupérer le panier actuel depuis la session
        $shoppingCart = $session->getSession()->get('shoppingCart', []);

        // Ajouter l'ID de l'article au panier
        if($repository->find($idProduct) != null){
            if(!empty($shoppingCart[$idProduct])){
                $shoppingCart[$idProduct]++;
            }
            else{
                $shoppingCart[$idProduct] = 1;
            }
            $session->getSession()->set('shoppingCart', $shoppingCart);
            $this->addFlash("success", "Le produit a bien été ajouté au panier");
        }
        else {
            $this->addFlash("error","Le produit que vous avez essayé d'ajouter à votre panier n'existe pas");
        }



        // Enregistrer le panier mis à jour dans la session

        return $this->redirectToRoute('shop'); // Rediriger vers la page d'accueil (ou une autre page)
    }


    #[Route('/product/{idProduct}/removeToShoppingCartItem', name: 'removeShoppingCartItem')]
    public function removeShoppingCartItem(int $idProduct, RequestStack $session): Response
    {
        // Récupérer le panier actuel depuis la session
        $shoppingCart = $session->getSession()->get('shoppingCart', []);

        // Ajouter l'ID de l'article au panier
        if(!empty($shoppingCart[$idProduct])){
            unset($shoppingCart[$idProduct]);
        }

        // Enregistrer le panier mis à jour dans la session
        $session->getSession()->set('shoppingCart', $shoppingCart);

        //dd($session->getSession()->get('shoppingCart'));
        return $this->redirectToRoute('shoppingCart'); // Rediriger vers la page d'accueil (ou une autre page)
    }

    /**
     * Afficher le contenu du panier.
     *
     * @return Response
     */
    #[Route('/shoppingCart', name: 'shoppingCart', options: ["expose" => false])]
    public function showShoppingCart(RequestStack $session, ProductRepository $repository): Response
    {
        // Récupérer le panier depuis la session
        $shoppingCart = $session->getSession()->get('shoppingCart', []);

        $panierwithData = [];

        foreach($shoppingCart as $id => $quantity){
            $panierwithData[] = [
                'product' => $repository->find($id),
                'quantity' => $quantity
            ];
        }

        //dd($panierwithData);

        return $this->render('shop/shoppingCart.html.twig', [
            'items' => $panierwithData,
        ]);

    }

}
