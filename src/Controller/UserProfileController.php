<?php

namespace App\Controller;



use App\Entity\Address;
use App\Entity\User;
use App\Form\UserEditType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserProfileController extends AbstractController
{
    #[Route('/user/profile', name: 'user_profile')]
    public function index()
    {
       return $this->render('user_profile/index.html.twig',[
           'user'=>$this->getUser()
           ]);
    }

    /**
     * @Route("/user/edit",name="user_edit")
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function edit(Request $request, EntityManagerInterface $manager){

            $form = $this->createForm(UserEditType::class,$this->getUser());
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()){
                $manager->persist($this->getUser());
                $manager->flush();
                return $this->redirectToRoute('user_profile');
            }
            return $this->renderForm('user_profile/edit.html.twig', [
                'formUserEdit' => $form,
            ]);
    }
}
