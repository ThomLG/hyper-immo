<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Dwelling;
use App\Form\CommentFormType;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DwellingController extends AbstractController
{
    #[Route('/{name}', name: 'dwelling_details')]
    public function details(Dwelling $dwelling, Request $request, EntityManagerInterface $entityManager, ManagerRegistry $doctrine): Response
    {

        // Partie formulaire de commentaires

        $comment = new Comment;

        // On génère le formulaire

        $commentForm = $this->createForm(CommentFormType::class, $comment);

        $commentForm->handleRequest($request);

        // Traitement du formulaire
        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $comment->setCreatedAt(new DateTimeImmutable());
            $comment->setDwelling($dwelling);

            // Récupération du contenu du champ parentId
            $parentId = $commentForm->get("parentId")->getData();

            // On va chercher le commentaire correspondant
            $entityManager = $doctrine->getManager();

            $parent = $entityManager->getRepository(Comment::class)->find($parentId);

            // On définit le parent 
            $comment->setParent($parent);
            $entityManager->persist($comment);
            $entityManager->flush();

            $this->addFlash('success', 'Message envoyé !');
            return $this->redirectToRoute('dwelling_details', ['name' => $dwelling->getName()]);
        }

        return $this->render('dwelling/index.html.twig', [
            'dwelling' => $dwelling,
            'commentForm' => $commentForm->createView()
        ]);
    }
}
