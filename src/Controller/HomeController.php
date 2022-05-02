<?php

namespace App\Controller;

use App\Entity\AnimalCategory;
use App\Entity\Newsletter;
use App\Form\NewsletterType;
use App\Repository\AnimalCategoryRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(AnimalCategoryRepository $animalCategoryRepository, ProductRepository $productRepository,PaginatorInterface $paginator,Request $request): Response
    {
        $categories = $animalCategoryRepository->findAll();
        $products = $paginator->paginate(
            $productRepository->findAll(),
            $request->query->getInt('page',1),
            4
        );
        $productsInProm = $paginator->paginate(
            $productRepository->findBy(['promo'=> true]),
//          dd($productsInProm);
            $request->query->getInt('page',1),
            4
        );

        return $this->render('home/index.html.twig', [
            'categories' => $categories,
            'products'=>$products,
            'productsInProm'=>$productsInProm
        ]);
    }




}
