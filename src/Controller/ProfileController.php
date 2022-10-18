<?php

namespace App\Controller;

//use App\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;


#[Route('/profil', name: 'app_profile_')]
class ProfileController extends AbstractController
{
    #[Route('/{lastName}', name: 'details')]
    public function profileDetails(UserInterface $user): Response
    { 
        return $this->render('profile/index.html.twig', compact('user'));

    }

    #[Route ('/{lastName}/edit', name:'edit')]
    public function profileEdit(Request $request, ManagerRegistry $doctrine)
    {
        $user=$this->getUser();
        $form=$this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        
        if($form->isSubmitted()&& $form->isValid()){
            $em = $doctrine->getManager();
            $em ->persist($user);
            $em ->flush();
            
            $this->addFlash('message', 'Profil mis à jour');
            return $this->redirectToRoute('app_profile_details', ['lastName'=>'user_lastName'

            ]);
        }
        return $this->render('profile/editprofile.html.twig', [
            'form' =>$form->createView()
        ]);
    }


}
