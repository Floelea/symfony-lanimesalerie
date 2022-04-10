<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Form\SearchProductType;
use App\Form\SearchUserType;
use App\Form\UserEditType;
use App\Repository\OrderRepository;
use App\Repository\ProductCategoryRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'admin')]
    public function index(UserRepository $userRepository,OrderRepository $orderRepository): Response
    {
        $today = new \DateTime();
        $sevenDaysAGo = new \DateInterval('P7D');
        $week = $today->sub($sevenDaysAGo);
        $newUserWeekAGo = count($userRepository->newUserSinceAWeek($week));
        $newOrderWeekAGo = count($orderRepository->newOrder($week));
        dump($newOrderWeekAGo);
        return $this->render('admin/index.html.twig', [
            'newUserWeekAGo'=> $newUserWeekAGo,
            'newOrderWeekAGo'=> $newOrderWeekAGo
        ]);
    }


    /**
     * @Route("/admin/user",name="admin_user")
     * @param UserRepository $userRepository
     * @return Response
     */
    public function searchUser(UserRepository $userRepository,Request $request)
    {
        $users = null;
        $form = $this->createForm(SearchUserType::class);
        $search = $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $users = $userRepository->search($search->get('mots')->getData());
        }
        $today = new \DateTime();
        $sevenDaysAGo = new \DateInterval('P7D');
        $week = $today->sub($sevenDaysAGo);
        $newUsers = $userRepository->newUserSinceAWeek($week);
        return $this->renderForm('admin/user.html.twig', [
            'formSearchUser'=> $form,
            'users'=> $users,
            'newUsers'=>$newUsers
        ]);
    }

    /**
     * @Route("/admin/user/new}",name="admin_userNew")
     * @return void
     */
    public function userNew(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $user->setCreatedAt(new \DateTime());
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('admin_user');
        }

        return $this->render('admin/userNew.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/user/edit/{id}",name="admin_userEdit")
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function userEdit(EntityManagerInterface $manager,Request $request,User $user)
    {
        $form = $this->createForm(UserEditType::class,$user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $manager->persist($user);
            $manager->flush();
            return $this->redirectToRoute('admin_user');
        }
        return $this->renderForm('admin/userEdit.html.twig', [
            'formUserEdit' => $form,
        ]);
    }

    /**
     * @Route("/admin/user/delete/{id}",name="admin_userDelete")
     * @param EntityManagerInterface $manager
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function userDelete(EntityManagerInterface $manager,User $user)
    {
       if($user){
           $manager->remove($user);
           $manager->flush();
       }
       return $this->redirectToRoute('admin_user');
    }


}
