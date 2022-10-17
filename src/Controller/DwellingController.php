<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DwellingController extends AbstractController
{
    #[Route('/dwelling', name: 'app_dwelling')]
    public function index(): Response
    {
        return $this->render('dwelling/index.html.twig', [
            'controller_name' => 'DwellingController',
        ]);
    }
}
