<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\ProductReview;
use App\Form\ReviewType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReviewController extends AbstractController
{
    #[Route('/review/{id}', name: 'review')]
    public function index(Request $request,Product $product,EntityManagerInterface $manager): Response
    {

        $note = $request->request->get('note');
        $reviewTitle = $request->request->get('reviewTitle');
        $reviewMessage = $request->request->get('reviewMessage');
//        dd($request);
        if ($note && $reviewMessage && $reviewTitle)
        {

            $review = new ProductReview();
            $review->setUser($this->getUser());
            $review->setProduct($product);
            $review->setTitle($reviewTitle);
            $review->setContent($reviewMessage);
            $review->setNote($note);
            $review->setCreatedAt(new \DateTime());
            $manager->persist($review);
            $manager->flush();
            return $this->redirectToRoute('product_show',['id'=>$product->getId()]);

        }
        return $this->renderForm('review/index.html.twig',[
            'product'=>$product
        ]);
    }


}
