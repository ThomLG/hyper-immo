<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin', name: 'adminpage_')]
class AdminController extends AbstractController
{

    #[Route('/utilisateurs', name: 'index')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }
}
