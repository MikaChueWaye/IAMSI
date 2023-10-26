<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Service\FlashMessageHelper;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/product/create', name: 'createProduct', methods: ['GET', 'POST'])]
    public function createProduct(Request $request, EntityManagerInterface $entityManager, FlashMessageHelper $flashHelper ): Response {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product, [
            'method' => 'POST',
            'action' => $this->generateURL('createProduct')
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($product);
            $entityManager->flush();
            $this->addFlash("success", "Produit créé!");
            return $this->redirectToRoute('shop');
        }
        $flashHelper->addFormErrorsAsFlash($form);
        return $this->render('product/createProduct.html.twig', ['form' => $form]);
    }

    #[Route('/product/{filter}/filter', name: 'filterProduct')]
    public function filterProduct(string $filter,ProductRepository $productRepository): Response
    {
        $productRepository->findBy(["type"=>$filter]);
        return $this->render('product/createProduct.html.twig');
    }

    #[Route('/product/{idProduct}/read', name: 'readProduct')]
    public function readProduct(int $idProduct, ProductRepository $productRepository): Response
    {
        $product = $productRepository->find($idProduct);
        return $this->render('product/product.html.twig', ["produit" => $product]);
    }

    #[Route('/product', name: 'product')]
    public function products(ProductRepository $productRepository): Response {
        $productList = $productRepository->findAll();
        return $this->render('product/productList.html.twig', ["products" => $productList]);
    }
}
