<?php

namespace App\Controller;

use App\Entity\Address;use App\Entity\Order;
use App\Entity\OrderItem;
use App\Form\OrderType;use App\Service\CartService;use Doctrine\ORM\EntityManagerInterface;use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;use Symfony\Component\HttpFoundation\Request;use Symfony\Component\HttpFoundation\Response;use Symfony\Component\HttpFoundation\Session\SessionInterface;use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    #[Route('/order', name: 'order')]
    public function index( CartService $cartService,Request $request,EntityManagerInterface $manager,SessionInterface $session): Response
    {
        $order = new Order();
        $form = $this->createForm(OrderType::class,$order);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $order->setUser($this->getUser());
            $order->setTotal($cartService->getTotal());
            $order->setCreatedAt(new \DateTime());
            $manager->persist($order);
            foreach ($cartService->getCart() as $item){
                $orderItem = new OrderItem();
                $orderItem->setCreatedAt(new \DateTime());
                $orderItem->setProduct($item['product']);
                $orderItem->setQuantity($item['quantity']);
                $orderItem->setOrderObject($order);
                $manager->persist($orderItem);
            }
            $manager->flush();
            $cartService->removeCart();
            return $this->redirectToRoute('home');
        }
        return $this->renderForm('order/index.html.twig', [
            'cartObject' => $cartService->getCart(),
            'total'=> $cartService->getTotal(),
            'formOrder'=>$form

        ]);
    }



}
