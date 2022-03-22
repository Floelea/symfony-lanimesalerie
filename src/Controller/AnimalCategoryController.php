<?php

namespace App\Controller;

use App\Entity\AnimalCategory;
use App\Form\AnimalCategoryType;
use App\Repository\AnimalCategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnimalCategoryController extends AbstractController
{
    #[Route('/admin/animal/category', name: 'animal_category')]
    public function index(AnimalCategoryRepository $repo): Response
    {
        $categories = $repo->findAll();
        return $this->render('animal_category/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/admin/animal/category/new",name="new_category")
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function new(Request $request, EntityManagerInterface $manager)
    {
        $animalCategory = new AnimalCategory();
        $form = $this->createForm(AnimalCategoryType::class,$animalCategory);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $manager->persist($animalCategory);
            $manager->flush();
           return $this->redirectToRoute('animal_category');
        }
        return $this->renderForm('animal_category/new.html.twig',[
            'formAnimalCategory'=>$form
        ]);
    }
}
