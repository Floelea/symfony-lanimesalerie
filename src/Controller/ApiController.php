<?php

namespace App\Controller;

use App\Repository\CartRepository;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    #[Route('/api', name: 'api')]
    public function index(ProductRepository $productRepository,OrderRepository $orderRepository ,UserRepository $userRepository): Response
    {
        $statistiques=[];
        //Date a J-1
        $dateNow = new \DateTime();
        $oneDayAGo = new \DateInterval('P1D');
        $oneDay = $dateNow->sub($oneDayAGo);
        $statistiques['oneDay']=$oneDay;
//        dd($oneDay);

        //Nombre de commande total
        $statistiques['countOrders']=$orderRepository->count([]);

        //Nombre de commandes depuis 1 jour
//        $dateNow = new \DateTime();
//        $oneDayAGo = new \DateInterval('P1D');
//        $oneDay = $dateNow->sub($oneDayAGo);
        $newOrderSinceADay = count($orderRepository->newOrder($oneDay));
//        dd($newOrderSinceADay);
        $statistiques['newOrderSinceADay']=$newOrderSinceADay;
        //        dd($newOrderSinceADay);

        //Montant des commandes depuis 1 jour
        $totalOrderSinceADay = $orderRepository->newOrder($oneDay);
        $MontantDesCommandesDepuisUnJour = 0;
        foreach($totalOrderSinceADay as $order){
            $MontantDesCommandesDepuisUnJour+=$order->getTotal();
        }
        $statistiques['MontantDesCommandesDepuisUnJour']=$MontantDesCommandesDepuisUnJour;
//        dd($MontantDesCommandesDepuisUnJour);

        //Panier moyen
        if ($newOrderSinceADay == 0){
            $averageCart = 0;
            $statistiques['panierMoyen']=$averageCart;
        }else{
            $averageCart = $MontantDesCommandesDepuisUnJour / $newOrderSinceADay;
            $statistiques['panierMoyen']=$averageCart;
        }


//        dd($averageCart);
//        dd($statistiques);

        //Nouveau Client
        $today = new \DateTime();
        $one = new \DateInterval('P1D');
        $onceUponOneDay = $today->sub($one);
        $newUserSinceADay = count($userRepository->newUserSinceAWeek($onceUponOneDay));
//        dd($newUserSinceADay);
        $statistiques['newUserDay']=$newUserSinceADay;

        return $this->json($statistiques,200);
    }

    /**
     * @Route("/api/user/{start}/to/{end}",name="api_user")
     *
     * @return void
     */
    public function user(UserRepository $userRepository,\DateTime $start,\DateTime $end)
    {

        $newClientByDate = count($userRepository->findByFromDate($start,$end)) ;
//        dd($newClientTwoDaysAGo);
        return $this->json($newClientByDate,200);
    }

    /**
     * @Route("/api/order/{start}/to/{end}",name="api_order")
     * @param OrderRepository $orderRepository
     * @param \DateTime $start
     * @param \DateTime $end
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function order(OrderRepository $orderRepository,\DateTime $start,\DateTime $end)
    {
        $newOrderByDate = count($orderRepository->findOrderByFromDate($start,$end));
        return $this->json($newOrderByDate,200);
    }

    /**
     * @Route("/api/order/sales/{start}/to/{end}",name="api_order_sales")
     * @param OrderRepository $orderRepository
     * @param \DateTime $start
     * @param \DateTime $end
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function sales(OrderRepository $orderRepository,\DateTime $start,\DateTime $end)
    {
        $newOrderByDate = $orderRepository->findOrderByFromDate($start,$end);
        $salesByDay = 0;
        foreach($newOrderByDate as $order){
            $salesByDay+=$order->getTotal();
        }
//        dd($salesByDay);
        return $this->json($salesByDay,200);
    }

    /**
     * @Route("/api/cart/{start}/to/{end}",name="api_cart")
     * @param CartRepository $cartRepository
     * @param \DateTime $start
     * @param \DateTime $end
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function cart(CartRepository $cartRepository,\DateTime $start,\DateTime $end)
    {
        $cartByDate = count($cartRepository->findCartByFromDate($start,$end));
//        dd($cartByDate);
        return $this->json($cartByDate,200);
    }

    /**
     * @Route("/api/average_cart/{start}/to/{end}",name="api_averageCart")
     * @param OrderRepository $orderRepository
     * @param \DateTime $start
     * @param \DateTime $end
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function averageCart(OrderRepository $orderRepository,\DateTime $start,\DateTime $end)
    {
        $newOrderByDate = $orderRepository->findOrderByFromDate($start,$end);
        $salesByDay = 0;
        foreach($newOrderByDate as $order){
            $salesByDay+=$order->getTotal();
        }
        $newOrderByDate = count($orderRepository->findOrderByFromDate($start,$end));
        if ($newOrderByDate == 0){
            $averageCart = 0;

        }else{
            $averageCart = $salesByDay / $newOrderByDate;

        }
       return $this->json($averageCart,200);
    }

}
