<?php

namespace App\Controller;

use App\Entity\ProductCategory;use App\Form\ProductCategoryType;
use App\Repository\ProductCategoryRepository;
use Doctrine\ORM\EntityManagerInterface;use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;use Symfony\Component\HttpFoundation\Request;use Symfony\Component\HttpFoundation\Response;use Symfony\Component\Routing\Annotation\Route;

class ProductCategoryController extends AbstractController
{
    #[Route('/product/category', name: 'product_category')]
    public function index(ProductCategoryRepository $productCategoryRepository): Response
    {
        $categories = $productCategoryRepository->findAll();
        return $this->render('admin/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/admin/product/category",name="admin_category")
     * @param ProductCategoryRepository $productCategoryRepository
     * @return Response
     */
    public function productCategory(ProductCategoryRepository $productCategoryRepository)
    {
        $categories = $productCategoryRepository->findAll();
        return $this->render('admin/category.html.twig',[
            'categories'=>$categories
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
            return $this->redirectToRoute('admin_category');
        }
        return $this->renderForm('product_category/new.html.twig',[
            'formProductCategory'=>$form
        ]);
    }

    /**
     * @Route("/admin/product/category/delete/{id}",name="delete_productCategory")
     * @param ProductCategory $productCategory
     * @param EntityManagerInterface $manager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function Delete(ProductCategory $productCategory,EntityManagerInterface $manager)
    {
        if($productCategory){
            $manager->remove($productCategory);
            $manager->flush();
        }
        return $this->redirectToRoute('admin_category');
    }

    /**
     * @Route("/admin/product/category/edit/{id}",name="edit_productCategory")
     * @param ProductCategory $productCategory
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function edit(ProductCategory $productCategory,Request $request,EntityManagerInterface $manager)
    {
        $form = $this->createForm(ProductCategoryType::class,$productCategory);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($productCategory);
            $manager->flush();
            return $this->redirectToRoute('admin_category');
        }
        return $this->renderForm('admin/categoryEdit.html.twig',[
            'formCategoryEdit'=>$form
        ]);
    }
}
