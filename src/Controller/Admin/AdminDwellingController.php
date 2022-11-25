<?php

namespace App\Controller\Admin;


use App\Entity\Dwelling;
use App\Form\AdminAddDwellingFormType;
use App\Repository\DwellingRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/dwellings', name: 'dwelling_admin_')]
class AdminDwellingController extends AbstractController
{

    #[Route('/', name: 'index')]
    public function showDwelling(DwellingRepository $dwellingRepository): Response
    {
        return $this->render('admin/dwelling/index.html.twig', [
            'dwellings' => $dwellingRepository->findAll()
        ]);
    }

    #[Route('/ajout', name: 'add')]
    public function addDwelling(Request $request, ManagerRegistry $doctrine, SluggerInterface $slugger): Response
    {
        $dwelling = new Dwelling;

        $form = $this->createForm(AdminAddDwellingFormType::class, $dwelling);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupération image uploadée
            /** @var UploadedFile $picture */
            $picture = $form->get('picture')->getData();

            // Renomme le nom du fichier
            if ($picture) {
                $fileName = pathinfo($picture->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFileName = $slugger->slug($fileName);
                $newPictureName = $safeFileName . '.' . $picture->guessExtension();

                // On déplace l'image vers son dossier 

                try {
                    $picture->move(
                        $this->getParameter('images_directory'),
                        $newPictureName
                    );
                } catch (FileException $e) {
                    //
                }

                $dwelling->setPicture($newPictureName);
            }

            $em = $doctrine->getManager();
            $em->persist($dwelling);
            $em->flush();

            return $this->redirectToRoute('dwelling_admin_index');
        }
        return $this->render('admin/dwelling/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/modifier/{id}', name: 'edit')]
    public function editDwelling(Dwelling $dwelling, Request $request, ManagerRegistry $doctrine, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(AdminAddDwellingFormType::class, $dwelling);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupération nouvelle image uploadée
            /** @var UploadedFile $picture */
            $picture = $form->get('picture')->getData();

            // Renomme le nom de la nouvelle image
            if ($picture) {
                $fileName = pathinfo($picture->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFileName = $slugger->slug($fileName);
                $newPictureName = $safeFileName . '.' . $picture->guessExtension();

                // On déplace l'image vers son dossier 

                try {
                    $picture->move(
                        $this->getParameter('images_directory'),
                        $newPictureName
                    );
                } catch (FileException $e) {
                    //
                }

                $dwelling->setPicture($newPictureName);
            }

            $em = $doctrine->getManager();
            $em->persist($dwelling);
            $em->flush();

            $this->addFlash('warning', 'Annonce modifiée avec succès !');
            return $this->redirectToRoute('dwelling_admin_index');
        }

        return $this->render('admin/dwelling/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }


    #[Route('/suppression/{id}', name: 'delete')]
    public function deleteDwelling(Dwelling $dwelling, ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $em->remove($dwelling);
        $em->flush();

        $this->addFlash('danger', 'Annonce supprimée avec succès !');
        return $this->redirectToRoute('dwelling_admin_index');
    }
}
