<?php

namespace App\Controller;

use App\Entity\ProductCategory;use App\Form\ProductCategoryType;use Doctrine\ORM\EntityManagerInterface;use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;use Symfony\Component\HttpFoundation\Request;use Symfony\Component\HttpFoundation\Response;use Symfony\Component\Routing\Annotation\Route;

class ProductCategoryController extends AbstractController
{
    #[Route('/product/category', name: 'product_category')]
    public function index(): Response
    {
        return $this->render('product_category/index.html.twig', [
            'controller_name' => 'ProductCategoryController',
        ]);
    }

    /**
     * @Route("/admin/product/category/new",name="new_productCategory")
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function new(Request $request, EntityManagerInterface $manager)
    {
        $productCategory = new ProductCategory();
        $form = $this->createForm(ProductCategoryType::class,$productCategory);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($productCategory);
            $manager->flush();
            return $this->redirectToRoute('product_category');
        }
        return $this->renderForm('product_category/new.html.twig',[
            'formProductCategory'=>$form
        ]);
    }
}
