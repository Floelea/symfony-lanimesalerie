<?php

namespace App\Controller;

use App\Repository\AnimalCategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function find(ProductRepository $repo,AnimalCategoryRepository $animalCategoryRepository): Response
    {
        $products = $repo->findAll();
        $categories = $animalCategoryRepository->findAll();
        return $this->render('home/index.html.twig', [
            'products' => $products,
            'categories'=>$categories
        ]);
    }

}
