<?php

namespace App\Controller;

use App\Entity\ProductSubCategory;use App\Form\ProductSubCategoryType;
use App\Repository\ProductCategoryRepository;
use App\Repository\ProductSubCategoryRepository;use Doctrine\ORM\EntityManagerInterface;use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;use Symfony\Component\HttpFoundation\Request;use Symfony\Component\HttpFoundation\Response;use Symfony\Component\Routing\Annotation\Route;

class ProductSubCategoryController extends AbstractController
{
    #[Route('/admin/product/subCategory', name: 'admin_subCategory')]
    public function productSubCategory(ProductCategoryRepository $productCategoryRepository): Response
    {
        $categories = $productCategoryRepository->findAll();

        return $this->render('admin/subCategory.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/product/subCategory/new",name="product_sub_category_new")
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function new(Request $request, EntityManagerInterface $manager)
    {
        $subCategory = new ProductSubCategory();
        $form = $this->createForm(ProductSubCategoryType::class,$subCategory);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($subCategory);
            $manager->flush();
            return $this->redirectToRoute('product_sub_category');
        }
        return $this->renderForm('product_sub_category/new.html.twig',[
            'formSubCategory'=>$form
        ]);
    }
}
