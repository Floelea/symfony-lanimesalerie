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
        $alreadyInBase = $newsletterRepository->findOneBy(['email' => $input]);
//            dd($alreadyInBase);

        if ($this->getUser()){
            if ($this->getUser()->getNewsletter() == null ){
                if ($alreadyInBase == null){
//                    dd('user pas demail et dans newsletter il ny est pas');
                        $newsletter = new Newsletter();
                        $newsletter->setEmail($request->request->get('newsletter'));
                        $newsletter->addUser($this->getUser());
                        $manager->persist($newsletter);
                        $manager->flush();
                    $this->addFlash(
                        'success',
                        " Vous êtes bien inscrit à notre newsletter !" );
                }else{
//                    dd('email present dans lentite newsletter');
                    $this->addFlash(
                        'warning',
                        "Cet email est déjà inscrit a notre newsletter, merci d'en saisir un nouveau !" );
                }
            }else{
//                dd('user est deja inscrit');
                $this->addFlash(
                    'warning',
                    'Vous êtes déjà inscrit a notre Newsletter !');
            }
        }else{
            if ($alreadyInBase == null){
//                dd('pas user connecte');
                $newsletter = new Newsletter();
                $newsletter->setEmail($request->request->get('newsletter'));
                $manager->persist($newsletter);
                $manager->flush();
                $this->addFlash(
                    'success',
                    " Vous êtes bien inscrit à notre newsletter !" );
            }else{
//                dd('email present dans lentite newsletter');
                $this->addFlash(
                    'warning',
                    "Cet email est déjà inscrit a notre newsletter, merci d'en saisir un nouveau !" );
            }
        }
        return $this->redirectToRoute('home');
    }
}

