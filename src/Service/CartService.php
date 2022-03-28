<?php

namespace App\Service;

use App\Entity\Product;
use App\Repository\CartRepository;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService
{
    private $requestStack;
    private $repo;
    private $cartRepo;

    public function __construct(RequestStack $requestStack,ProductRepository $repo,CartRepository $cartRepo){
        $this->requestStack=$requestStack;
        $this->repo=$repo;
        $this->cartRepo=$cartRepo;
    }

    public function getCart()
    {
        $session = $this->requestStack->getSession();
        $cart=$session->get('cart',[]);
        $cartObject=[];
        foreach ($cart as $productId=>$quantity){
            $item=[
                'product'=>$this->repo->find($productId),
                'quantity'=>$quantity
            ];
            $cartObject[]=$item;
        }
        return $cartObject;
    }

    public function getTotal()
    {
        $total=0;
        foreach ($this->getCart() as $item){
            $total+=($item['product']->getPriceHt() * $item['quantity']);
        }
        return $total;
    }

    public function addProduct(Product $product){
        $session = $this->requestStack->getSession();
        $cart = $session->get("cart",[]);
        $productId = $product->getId();
        if(isset($cart[$productId])){
            $cart[$productId]++;
        }else{
            $cart[$productId]=1;
        }
        $session->set("cart",$cart);
    }

    public function removeProduct(Product $product)
    {
        $session = $this->requestStack->getSession();
        $productId = $product->getId();
        $cart = $session->get("cart", []);
        if(isset($cart[$productId])){
            $cart[$productId]--;
            if($cart[$productId] == 0 ){
                unset($cart[$productId]);
            }
        }
        $session->set("cart",$cart );
    }

    public function removeRow(Product $product)
    {
        $session = $this->requestStack->getSession();
        $cart = $session->get('cart',[]);
        $productId = $product->getId();
        if (isset($cart[$productId])){
            unset($cart[$productId]);
        }
        $session->set('cart',$cart);
    }

    public function countItems()
    {
        $count = 0;
        $session = $this->requestStack->getSession();
        $cart = $session->get('cart',[]);
        foreach ($cart as $productId=>$quantity){
            $count+=$quantity;
        }
        return $count;
    }

    public function getSessionId()
    {
        $session = $this->requestStack->getSession()->getId();
        return $session;
    }

    public function isCartInDataBase()
    {
        return $this->cartRepo->findOneBy(['session_id'=>$this->getSessionId()]);
    }
}