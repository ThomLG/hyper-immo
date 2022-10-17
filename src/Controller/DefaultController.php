<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\DwellingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'homepage')] // Route pour afficher la page d'accueil
    public function showCategories(CategoryRepository $categoryRepository, DwellingRepository $dwellingRepository): Response
    {
        return $this->render('default/index.html.twig', [
            'categories' => $categoryRepository->findBy(
                [],
                ['name' => 'asc']
            ),
            'dwellings' => $dwellingRepository->findBy(
                [],
                ['name' => 'asc']
            )
        ]);
    }
}
