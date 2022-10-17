<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/category', name: 'app_category')]
class CategoryController extends AbstractController
{
    #[Route('/{name}', name: 'app_category_name')]
    public function index(): Response
    {
        return $this->render('category/index.html.twig', [
           
        ]);
    }
}
