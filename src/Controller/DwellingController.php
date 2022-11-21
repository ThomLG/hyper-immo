<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentFormType;
use App\Repository\CommentRepository;
use App\Repository\DwellingRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/dwelling', name: 'dwelling_')]
class DwellingController extends AbstractController
{
    #[Route('/{name}', name: 'details')]
    public function details($name, DwellingRepository $dwellingRepository, CommentRepository $commentRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $dwelling = $dwellingRepository->findOneBy(['name' => $name]);

        $comment = new Comment();
        $form = $this->createForm(CommentFormType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setCreatedAt(new DateTimeImmutable());
            $comment->setDwelling($dwelling);

            $entityManager->persist($comment);
            $entityManager->flush();

            $this->addFlash('success', 'Message envoyé !');
            return $this->redirectToRoute('dwelling_details', ['name' => $dwelling->getName()]);
        }


        return $this->render('dwelling/index.html.twig', [
            'dwelling' => $dwelling,
            'commentForm' => $form->createView()
        ]);
    }
}
