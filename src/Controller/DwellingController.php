<?php

namespace App\Controller;

use App\Entity\Dwelling;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DwellingController extends AbstractController
{
    #[Route('/{name}', name: 'dwelling_details')]
    public function details(Dwelling $dwelling): Response
    {
        return $this->render('dwelling/index.html.twig', compact('dwelling'));
    }
}
