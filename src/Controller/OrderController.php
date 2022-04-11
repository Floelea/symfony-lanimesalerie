<?php

namespace App\Controller;

use App\Entity\Address;use App\Entity\Order;
use App\Entity\OrderItem;
use App\Form\OrderEditType;
use App\Form\OrderType;
use App\Repository\OrderRepository;
use App\Service\CartService;use Doctrine\ORM\EntityManagerInterface;use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;use Symfony\Component\HttpFoundation\Request;use Symfony\Component\HttpFoundation\Response;use Symfony\Component\HttpFoundation\Session\SessionInterface;use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    #[Route('/order/{id}', name: 'order')]
    public function new( Address $address, CartService $cartService,Request $request,EntityManagerInterface $manager,SessionInterface $session): Response
    {
        $order = new Order();
        $form = $this->createForm(OrderType::class,$order);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $order->setUser($this->getUser());
            $order->setTotal($cartService->getTotal());
            $order->setAddress($address);
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
            'formOrder'=>$form,
            'address'=>$address

        ]);
    }

    /**
     * @Route("/admin/order/delete/{id}",name="delete_order")
     * @param Order $order
     * @param EntityManagerInterface $manager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(Order $order,EntityManagerInterface $manager)
    {
        if($order){
            $manager->persist($order);
            $manager->flush();
        }
        return $this->redirectToRoute('admin_order');
    }

    /**
     * @Route("/admin/order",name="admin_order")
     * @param OrderRepository $orderRepository
     * @return Response
     */
    public function findOrderFromOneWeek(OrderRepository $orderRepository)
    {
        $today = new \DateTime();
        $sevenDaysAGo = new \DateInterval('P7D');
        $week = $today->sub($sevenDaysAGo);
        $newOrders = $orderRepository->newOrderSinceAWeek($week);
//        dd($newOrders);
        return $this->render('order/show.html.twig',[
            'newOrders' => $newOrders
        ]);
    }

    /**
     * @Route("/admin/order/waiting",name="order_waiting")
     * @param OrderRepository $orderRepository
     * @return Response
     */
    public function findByOrderStatusIsNull(OrderRepository $orderRepository)
    {
        $ords = $orderRepository->findBy(array(
            'orderStatus'=>null
        ));
//        dd($ords);
        return $this->render('order/waiting.html.twig',[
            'ords'=>$ords
        ]);
    }

    /**
     * @Route("/admin/order/edit/{id}",name="order_edit")
     * @param Order $order
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function edit(Order $order,Request $request,EntityManagerInterface $manager)
    {
        $form = $this->createForm(OrderEditType::class,$order);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $manager->persist($order);
            $manager->flush();
            return $this->redirectToRoute('admin_order');
        }
        return $this->renderForm('order/edit.html.twig',[
            'formOrderEdit'=>$form,
            'order'=>$order
        ]);
    }

}
