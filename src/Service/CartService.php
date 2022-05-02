<?php

namespace App\Service;

use App\Entity\Product;use App\Repository\CartRepository;use App\Repository\ProductRepository;use Doctrine\ORM\EntityManagerInterface;use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService
{
    private $session;
    private $repo;
    private $cartRepo;
    private $manager;

    public function __construct(SessionInterface $session,ProductRepository $repo,CartRepository $cartRepo,EntityManagerInterface $manager){
        $this->session = $session;
        $this->repo=$repo;
        $this->cartRepo=$cartRepo;
        $this->manager=$manager;

    }

    public function getCart()
    {
        $cart=$this->session->get('cart',[]);
        $cartObject=[];
        foreach ($cart as $productId=>$quantity){
            $item=[
                'product'=>$this->repo->find($productId),
                'quantity'=>$quantity
            ];
            $cartObject[]=$item;
        }
//        dd($cartObject);
        return $cartObject;
    }

    public function getTotal()
    {
        $total=0;
        foreach ($this->getCart() as $item){
            $total+=($item['product']->getPriceHt() * (1+$item['product']->getTva()->getRate()/100) * $item['quantity']);
        }
        return $total ;
    }

    public function addProduct(Product $product){
        $cart = $this->session->get("cart",[]);
        $productId = $product->getId();
        if(isset($cart[$productId])){
            $cart[$productId]++;
        }else{
            $cart[$productId]=1;
        }
        $this->session->set("cart",$cart);
    }

    public function removeProduct(Product $product)
    {
        $productId = $product->getId();
        $cart = $this->session->get("cart", []);
        if(isset($cart[$productId])){
            $cart[$productId]--;
            if($cart[$productId] == 0 ){
                unset($cart[$productId]);
            }
        }
        $this->session->set("cart",$cart );
    }

    public function removeRow(Product $product)
    {
        $cart = $this->session->get('cart',[]);
        $productId = $product->getId();
        if (isset($cart[$productId])){
            unset($cart[$productId]);
        }
        $this->session->set('cart',$cart);
    }

    public function removeCart()
    {
        $this->session->remove('cart');
        $this->manager->remove($this->isCartInDataBase());
        $this->manager->flush();
    }

    public function countItems()
    {
        $count = 0;
        $cart = $this->session->get('cart',[]);
        foreach ($cart as $productId=>$quantity){
            $count+=$quantity;
        }
        return $count;
    }

    public function getSessionId()
    {
        return $this->session->getId();
    }

    public function isCartInDataBase()
    {
        return $this->cartRepo->findOneBy(['sessionId'=>$this->getSessionId()]);
    }
}