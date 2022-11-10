<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\UserAuthenticator;
use App\Service\JWTService;
use App\Service\SendMailService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/inscription', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, UserAuthenticator $authenticator, EntityManagerInterface $entityManager, SendMailService $mail, JWTService $jwt): Response
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

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email


            // On va générer le JWT de l'utilisateur

            // On crée le header
            $header = [
                'typ' => 'JWT',
                'alg' => 'HS256'
            ];

            // on crée le payload

            $payload = [
                'user_id' => $user->getId() // je vais chercher l'id du user
            ];

            // On génère le token
            $token = $jwt->generate($header, $payload, $this->getParameter('app.jwtsecret'));

            // On envoie un mail

            $mail->send(
                'no-reply@hyper-immo.net', // from
                $user->getEmail(), // to
                'Activation de votre compte Hyper Immo', // subject
                'register', // template
                compact('user', 'token'), // context on passe aussi le token pour l'activation du compte
            );

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/verification/{token}', name: 'user_verification')]
    public function verifyUser($token, JWTService $jwt, UserRepository $userRepository, EntityManagerInterface $em): Response
    {
        // on vérifie si le token est valide, n'a pas expiré et n'a pas été modifié 

        if ($jwt->isValid($token) && !$jwt->isExpired($token) && $jwt->check($token, $this->getParameter('app.jwtsecret'))) {

            // Récupération du payload
            $payload = $jwt->getPayload($token);

            // On récupère le user du token
            $user = $userRepository->find($payload['user_id']);

            //On vérifie que l'user existe et n'a pas encore activé son compte
            if ($user && !$user->getIsVerified()) {
                $user->setIsVerified(true);
                $em->flush($user);
                $this->addFlash('success', 'Votre compte est activé');
                return $this->redirectToRoute('app_profile_details', ['lastName' => $this->getUser()->getLastName()]); // ça marche qd même malgré le surlignage en rouge
            }
        }
        // Si un pblm se pose dans le token
        $this->addFlash('danger', 'Le lien est invalide ou a expiré');
        return $this->redirectToRoute('app_login');
    }

    #[Route('/renvoi_verification', name: 'verif_resend')]
    public function resendVerif(JWTService $jwt, SendMailService $mail, UserRepository $userRepository): Response
    {
        $user = $this->getUser();

        if (!$user) {
            $this->addFlash('danger', 'Vous devez être connecté pour accéder à cette page');
            return $this->redirectToRoute('app_login');
        }

        if ($user->getIsVerified()) {
            $this->addFlash('warning', 'Votre compte est déjà activé');
            return $this->redirectToRoute('app_profile_details', ['lastName' => $this->getUser()->getLastName()]);
        }

        // On génère le JWT de l'utilisateur
        // On crée le Header
        $header = [
            'typ' => 'JWT',
            'alg' => 'HS256'
        ];

        // On crée le Payload
        $payload = [
            'user_id' => $user->getId()
        ];

        // On génère le token
        $token = $jwt->generate($header, $payload, $this->getParameter('app.jwtsecret'));

        // On envoie un mail
        $mail->send(
            'no-reply@monsite.net',
            $user->getEmail(),
            'Activation de votre compte sur le site e-commerce',
            'register',
            compact('user', 'token')
        );
        
        $this->addFlash('success', 'Email de vérification envoyé');
        return $this->redirectToRoute('app_profile_details', ['lastName' => $this->getUser()->getLastName()]);
    }
}
