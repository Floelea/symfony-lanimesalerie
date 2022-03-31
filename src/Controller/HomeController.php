<?php

namespace App\Controller;

use App\Entity\AnimalCategory;
use App\Repository\AnimalCategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(AnimalCategoryRepository $animalCategoryRepository, ProductRepository $productRepository): Response
    {
        $categories = $animalCategoryRepository->findAll();
        $products = $productRepository->findAll();
        return $this->render('home/index.html.twig', [
            'categories' => $categories,
            'products'=>$products
        ]);
    }
}
