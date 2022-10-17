<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/profil', name: 'app_profile')]
class ProfileController extends AbstractController
{
    #[Route('/{lastName}', name: 'app_profile_details')]
    public function profileDetails(User $user): Response
    { 
        return $this->render('profile/index.html.twig', compact('user'));

    }
}
