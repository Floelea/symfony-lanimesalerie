<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchBarController extends AbstractController
{
    #[Route('/search/bar', name: 'app_search_bar')]
    public function index(Request $request,ProductRepository $productRepository): Response
    {
        $input = $request->request->get('searchBar');
//        dd($input);
        $productsFind = $productRepository->searchBar($input);
        $howManyProductsFind = count($productRepository->searchBar($input));
//        dd($productsFind);
        return $this->render('search_bar/index.html.twig', [
            'productsFind' => $productsFind,
            'howManyProductsFind'=>$howManyProductsFind,
            'input'=>$input
        ]);
    }
}
