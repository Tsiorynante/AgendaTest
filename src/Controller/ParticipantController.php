<?php

namespace App\Controller;

use App\Entity\Participants;
use App\Form\ParticipantsType;
use App\Repository\ParticipantsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ParticipantController extends AbstractController
{
    #[Route('/participants', name:'participants' )]
    public function listParticipant(ParticipantsRepository $participantsRepository): Response
    {
        $participants = $participantsRepository->findAll();
        return $this->render('participant/listeParticipant.html.twig',[
            'controller_name' => 'PartcipantController',
            'participants' => $participants,
        ]);
    }
    
    #[Route('/participants/ajout', name:'participants_add' )]
    
    public function addParticipant(Request $request, EntityManagerInterface $manager): Response  
    {
        $participants= new Participants();
        $form = $this->createForm(ParticipantsType::class, $participants);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($participants);
            $manager->flush();

            return $this->redirectToRoute('participants');
        }

        return $this->render('participant/ajout.html.twig',[
            'form' => $form->createView()
        ]);
    }
    
    #[Route('/participants/modifier/{id}', name:'participants_up')]
    public function modifparticipant(Participants $participants, Request $request, EntityManagerInterface $manager ){
     
        $form= $this->createFormBuilder($participants);
        
        $form
            ->add('nom')
            ->add('prenom')
            ->add('email')
            ->add('telephone');

        
        $form = $form->getForm();
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($participants);
            $manager->flush();

            return $this->redirectToRoute('participants');
        }

        return $this->render('participant/modifier.html.twig',[
            'form' => $form->createView()
        ]);
    }

    #[Route('/participants/supprimer/{id}', name:'participants_supp')]
    public function suppparticipant(EntityManagerInterface $manager, ParticipantsRepository $ParticipantsRepository, $id)
    {
        $participant = $ParticipantsRepository->findOneBy(array('id' => $id));

        $manager->remove($participant);
        $manager->flush();

        return $this->redirectToRoute('participants');
    }

}