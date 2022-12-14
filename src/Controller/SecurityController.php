<?php

namespace App\Controller;

use App\Form\ResetPasswordFormType;
use App\Form\ResetPasswordRequestFormType;
use App\Repository\UserRepository;
use App\Service\SendMailService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }


    #[Route('/forgotten_password', name: 'forgotten_password')]
    public function forgottenPassword(Request $request, UserRepository $userRepository, TokenGeneratorInterface $tokenGeneratorInterface, EntityManagerInterface $entityManager, SendMailService $mail): Response
    {
        $form = $this->createForm(ResetPasswordRequestFormType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On cherche l'user par l'email 
            $user = $userRepository->findOneByEmail($form->get('email')->getData());

            // on cherche si on trouve un user avec l'email ??crit dans le formulaire 
            if ($user) {
                // On g??n??re un token pour r??initialiser le mdp
                $token = $tokenGeneratorInterface->generateToken();
                $user->setResetToken($token);
                $entityManager->persist($user);
                $entityManager->flush();

                // Generation du lien pour r??initialiser le mdp
                $url = $this->generateUrl('reset_pass', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL); // Nom de la route, le token et la g??n??ration de l'url

                // On cr??e les donn??es du mail

                $context = compact('url', 'user');

                // Envoi du mail

                $mail->send(
                    'no-reply@hyper-immo', // from
                    $user->getEmail(), // to
                    'R??initialisation de votre mot de passe', // Email Title
                    'password_reset', // template
                    $context // context
                );

                $this->addFlash('success', 'Email envoy?? avec succ??s');
                return $this->redirectToRoute('app_login');
            }

            $this->addFlash('danger', 'Une erreur est survenue !');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/reset_password_request.html.twig', [
            'requestPassForm' => $form->createView()
        ]);
    }

    #[Route('oubli-pass/{token}', name: 'reset_pass')]
    public function resetPass(
        string $token, Request $request, UserRepository $userRepository, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        // v??rification du token
        $user = $userRepository->findOneByResetToken($token);
        
        if($user){
            $form = $this->createForm(ResetPasswordFormType::class);

            $form->handleRequest($request);

            if($form->isSubmitted()&& $form->isValid()){
                // On efface le token

                $user->setResetToken('');
                $user->setPassword(
                    $passwordHasher->hashPassword($user,
                        $form->get('password')->getData()
                    ) // prend le user et le mot de passe entr?? ds le form
                );
                $entityManager->persist($user);
                $entityManager->flush();

                $this->addFlash('success', 'mot de passe chang?? avec succ??s');
                return $this->redirectToRoute('app_login');

            }

            return $this->render('security/reset_password.html.twig', [
                'passForm'=>$form->createView()
            ]);
        }

        // Sinon 
        $this->addFlash('danger', 'Une erreur est survenue !');
        return $this->redirectToRoute('app_login');
    }
}
