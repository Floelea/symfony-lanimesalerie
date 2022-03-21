<?php

namespace App\Controller;



use App\Entity\User;
use App\Form\UserEditType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserProfileController extends AbstractController
{
    #[Route('/user/profile/{id}', name: 'user_profile', priority: 2)]
    public function index(User $user,Request $request,EntityManagerInterface $manager): Response
    {
        if($user == $this->getUser()){
            $form = $this->createForm(UserEditType::class,$user);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()){
               $manager->persist($user);
               $manager->flush();
               return $this->redirectToRoute('home');
            }
            return $this->renderForm('user_profile/index.html.twig', [
                'formUserEdit' => $form,
            ]);
        }
        return $this->redirectToRoute('home');
    }
}
