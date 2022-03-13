<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/product', name: 'product')]
    public function index(ProductRepository $repo,PaginatorInterface $paginator, Request $request): Response
    {
        $products = $paginator->paginate(
            $repo->findAll(),
            $request->query->getInt('page',1),
            4
        );
        return $this->render('product/index.html.twig', [
            'products' => $products,
        ]);
    }
}
