<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnimalCategoryController extends AbstractController
{
    #[Route('/animal/category', name: 'app_animal_category')]
    public function index(): Response
    {
        return $this->render('animal_category/index.html.twig', [
            'controller_name' => 'AnimalCategoryController',
        ]);
    }
}
