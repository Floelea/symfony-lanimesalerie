<?php

namespace App\Controller;

use App\Entity\Product;use App\Entity\ProductCategory;use App\Form\ProductType;
use App\Form\SearchProductType;
use App\Repository\ProductRepository;use Doctrine\ORM\EntityManagerInterface;use Knp\Component\Pager\PaginatorInterface;use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;use Symfony\Component\HttpFoundation\Request;use Symfony\Component\HttpFoundation\Response;use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/product', name: 'product')]
    public function index(ProductRepository $repo,PaginatorInterface $paginator, Request $request): Response
    {
        $products = $paginator->paginate(
            $repo->findAll(),
            $request->query->getInt('page',1),
            10
        );
        return $this->render('product/index.html.twig', [
            'products' => $products,
        ]);
    }

    /**
     * @Route("/admin/new/product",name="new_product")
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function new(Request $request,EntityManagerInterface $manager)
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class,$product);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $product->setCreatedAt(new \DateTime());
            $manager->persist($product);
            $images = $form->getData()->getImages();
            foreach ($images as $image){
                $image->setProduct($product);
                $manager->persist($image);
            }
            $manager->flush();
            return $this->redirectToRoute('product');
        }
        return $this->renderForm('product/new.html.twig',[
            'formProduct'=>$form
        ]);
    }

    /**
     * @Route("/admin/product",name="admin_product")
     * @return Response
     */
    public function searchProduct(Request $request,ProductRepository $productRepository)
    {
        $products=null;
        $form = $this->createForm(SearchProductType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $criteria = $form->getData();
//           dd($criteria);
            $products = $productRepository->findProduct($criteria);
//           dd($products);
        }
        return $this->renderForm('admin/product.html.twig',[
            'formSearchProduct'=>$form,
            'products'=>$products
        ]);
    }


}
