<?php

namespace App\Controller;

use App\Entity\Newsletter;
use App\Entity\User;
use App\Form\NewsletterType;
use App\Repository\NewsletterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NewsletterController extends AbstractController
{
    /**
     * @Route("/newsletter",name="newsletter")
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function addNewsletter(Request $request, EntityManagerInterface $manager, NewsletterRepository $newsletterRepository): Response
    {
        $input = $request->request->get('newsletter');
//        dd($input);
        $base = $newsletterRepository->findOneBy(['email' => $input]);
//        dd($base->getEmail());


        if ($base == null) {
//            dd('ok');

            $newsletter = new Newsletter();
            $newsletter->setEmail($request->request->get('newsletter'));
            if ($this->getUser()){
                $newsletter->addUser($this->getUser());
            }
            $manager->persist($newsletter);
            $manager->flush();
        }else{
            dd('email deja inscrit');
        }
        return $this->redirectToRoute('home');
    }

}
//test
