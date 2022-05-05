<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\Cart;use App\Entity\Product;
use App\Entity\User;
use App\Service\CartService;use Doctrine\ORM\EntityManagerInterface;use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;use Symfony\Component\HttpFoundation\Response;use Symfony\Component\HttpFoundation\Session\SessionInterface;use Symfony\Component\Routing\Annotation\Route;


class CartController extends AbstractController
{
    #[Route('/cart', name: 'cart')]
    public function index(CartService $cartService,SessionInterface $session): Response
    {

//        $addresses = $this->getUser()->getAddress();
        return $this->render('cart/index.html.twig', [
            'cartObject' => $cartService->getCart(),
            'total'=> $cartService->getTotal(),

//            'addresses'=>$addresses

        ]);
    }

    /**
     * @Route("/cart/add/{id}",name="cart_add")
     * @param SessionInterface $session
     * @param Product $product
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addProduct(CartService $cartService,Product $product,EntityManagerInterface $manager)
    {
        $cartService->addProduct($product);

        $cart = $cartService->isCartInDataBase();

        if(!$cart){
            $cart = new Cart();
            if($this->getUser()){
                $cart->setUser($this->getUser());
            }
            $cart->setSessionId($cartService->getSessionId());
        }
        $cart->setTotal($cartService->getTotal());
        $cart->setCreatedAt(new \DateTime());
        $manager->persist($cart);
        $manager->flush();
        return $this->redirectToRoute('cart');

    }

    /**
     * @Route("/cart/remove/{id}",name="cart_remove")
     * @param Product $product
     * @param SessionInterface $session
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeProduct(Product $product,CartService $cartService,EntityManagerInterface $manager)
    {
        $cart = $cartService->isCartInDataBase();
        if ($product)
        {
            $cartService->removeProduct($product);
        }
        $manager->persist($cart);
        $manager->flush();

        return $this->redirectToRoute('cart');
    }

    /**
     * @Route("/cart/removerow/{id}",name="cart_removerow")
     * @param Product $product
     * @param CartService $cartService
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeRow(Product $product,CartService $cartService)
    {
        if ($product) {
            $cartService->removeRow($product);
        }
        return $this->redirectToRoute('cart');
    }

    /**
     * @Route("/cart/removeCart",name="cart_removeCart")
     */
    public function removeCart(CartService $cartService)
    {
        $cartService->removeCart();
        return $this->redirectToRoute('home');
    }




}
