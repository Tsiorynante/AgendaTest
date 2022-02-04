<?php

namespace App\Controller;

use App\Entity\Entretien;
use App\Form\EntretienType;
use App\Repository\EntretienRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/entretien')]
class EntretienController extends AbstractController
{
    #[Route('/', name: 'entretien_index', methods: ['GET'])]
    public function index(EntretienRepository $entretienRepository): Response
    {
        return $this->render('entretien/index.html.twig', [
            'entretiens' => $entretienRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'entretien_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $entretien = new Entretien();
        $form = $this->createForm(EntretienType::class, $entretien);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($entretien);
            $entityManager->flush();

            return $this->redirectToRoute('entretien_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('entretien/new.html.twig', [
            'entretien' => $entretien,
            'form' => $form,
        ]);
    }

    #[Route('/supprimer/{id}', name:'entretien_delete')]
    public function suppEntretien(EntityManagerInterface $manager, EntretienRepository $EntretienRepository, $id)
    {
        $entretien = $EntretienRepository->findOneBy(array('id' => $id));

        $manager->remove($entretien);
        $manager->flush();

        return $this->redirectToRoute('entretien_index');
    }

    #[Route('/show/{id}', name: 'entretien_show', methods: ['GET'])]
    public function show(Entretien $entretien): Response
    {
        return $this->render('entretien/show.html.twig', [
            'entretien' => $entretien,
        ]);
    }

    #[Route('/{id}/edit', name: 'entretien_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Entretien $entretien, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EntretienType::class, $entretien);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $entityManager->flush();

            return $this->redirectToRoute('entretien_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('entretien/edit.html.twig', [
            'entretien' => $entretien,
            'form' => $form,
        ]);
    }
    
}
