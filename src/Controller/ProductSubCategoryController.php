<?php

namespace App\Controller;

use App\Entity\ProductSubCategory;use App\Form\ProductSubCategoryType;use App\Repository\ProductSubCategoryRepository;use Doctrine\ORM\EntityManagerInterface;use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;use Symfony\Component\HttpFoundation\Request;use Symfony\Component\HttpFoundation\Response;use Symfony\Component\Routing\Annotation\Route;

class ProductSubCategoryController extends AbstractController
{
    #[Route('/product/subCategory', name: 'product_sub_category')]
    public function index(ProductSubCategoryRepository $repo): Response
    {
        $subCategories = $repo->findAll();

        return $this->render('product_sub_category/index.html.twig', [
            'subCategories' => $subCategories
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
