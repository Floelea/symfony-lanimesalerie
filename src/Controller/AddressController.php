<?php

namespace App\Controller;

use App\Entity\Address;
use App\Form\UserAddressType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AddressController extends AbstractController
{
    #[Route('/address', name: 'app_address')]
    public function index(): Response
    {
        return $this->render('address/index.html.twig', [
            'controller_name' => 'AddressController',
        ]);
    }

    /**
     * @Route("/user/address/new",name="new_user_address")
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function new(Request $request, EntityManagerInterface $manager){
        $address = new Address();
        $form = $this->createForm(UserAddressType::class,$address);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $address->setUser($this->getUser());
            $manager->persist($address);
            $manager->flush();
            return $this->redirectToRoute('user_profile',['id'=>$address->getUser()->getId()]);
        }
        return $this->renderForm('address/new.html.twig',[
            'formUserAddress'=>$form
        ]);
    }

    /**
     * @Route("/user/address/edit/{id}",name="userAddress_edit")
     * @param Address $address
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function edit(Address $address,Request $request,EntityManagerInterface $manager)
    {
        $form = $this->createForm(UserAddressType::class,$address);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $manager->persist($address);
            $manager->flush();
            return $this->redirectToRoute('user_profile');
        }
        return $this->renderForm('address/edit.html.twig',[
            'formAddressEdit'=>$form
        ]);
    }
}
