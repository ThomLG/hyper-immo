<?php

namespace App\Controller;

use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/category', name: 'app_category_')]
class CategoryController extends AbstractController
{
    #[Route('/{name}', name: 'name')]
    public function index(Category $category): Response
    {
        $dwellings= $category->getDwellings();
        // on va chercher la liste des habitations de la catégorie
        return $this->render('category/index.html.twig', compact('category', 'dwellings'));
    }
}
