<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Service\Mailer;
use Doctrine\ORM\EntityManagerInterface;
use http\Message;
use phpDocumentor\Reflection\Type;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager,Mailer $mailer): Response
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
            $user->setToken($this->generateToken());
            $user->setIsVerified(false);
            $user->setCreatedAt(new \DateTime());
            $entityManager->persist($user);
            $entityManager->flush();
            $mailer->sendEmail($user->getEmail(),$user->getToken());
            // do anything else you need here, like send an email
            $this->addFlash("success", "Inscription réussie");
            return $this->redirectToRoute('home');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/register/confirm/{token}",name="register_confirm")
     *
     */
    public function confirmRegistration( string $token,UserRepository $userRepository,EntityManagerInterface $entityManager)
    {
        $user = $userRepository->findOneBy(['token'=>$token]);
        if($user){
            $user->setToken(null);
            $user->setIsVerified(true);
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('home');
            $this->addFlash("success","Compte validé");
        }else{
            $this->addFlash("error","Ce compte n'existe pas");
            return $this->redirectToRoute('home');
        }

    }

    /**
     * @return string
     * @throws \Exception
     */
    private function generateToken()
    {
        return rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
    }

    /**
     * @Route("/signin",name="signin")
     */
    public function signin()
    {
        return $this->render('registration/signin.html.twig');

    }

    /**
     * @Route("/signout",name="signout")
     */
    public function signout()
    {

    }
}
