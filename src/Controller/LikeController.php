<?php

namespace App\Controller;

use App\Entity\Like;
use App\Entity\Product;
use App\Repository\LikeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LikeController extends AbstractController
{
    #[Route('/product/like/{id}', name: 'like')]
    public function index(LikeRepository $likeRepository,Product $product,EntityManagerInterface $manager): Response
    {
        $like = $likeRepository->findOneBy([
            'user'=>$this->getUser(),
            'product'=>$product
        ]);

        if(!$like)
        {
            $like = new Like();
            $like->setUser($this->getUser());
            $like->setProduct($product);
            $manager->persist($like);
            $liked = true;
        }else{
            $manager->remove($like);
            $liked = false;
        }
        $manager->flush();

        $count = $likeRepository->count(['product'=>$product]);
        $reponse = [
            'liked'=>$liked,
            'count'=>$count
        ];
        return $this->json($reponse,200);
        return $this->redirectToRoute('product_show',['id'=>$product->getId()]);

    }
}
