<?php

namespace App\Controller;

use App\Entity\Participants;
use App\Repository\EntretienRepository;
use App\Repository\ParticipantsRepository;
use Doctrine\ORM\Repository\Exception\InvalidFindByCall;
use PHPUnit\Util\Json;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'main')]
    public function index(EntretienRepository $EntretienRepository): Response
    {
        $events = $EntretienRepository->findAll();
       
        $em =[];
        
        foreach($events as $event)
        {

            $em[]= 
            [
                'id'=> $event->getId(),
                'title'=> $event->getTitre(),
                'start'=> $event->getDateDebut()->format('Y-m-d H:i:s'),
                'end'=> $event->getDateFin()->format('Y-m-d H:i:s'),                
                'description'=> $event->getLieu(),
                'color' => 'darkslateblue',
                'textColor' => 'yellow  ',
                'borderColor' => 'green'
            ];
            
            
        }
      
        $data = json_encode($em);
        return $this->render('main/index.html.twig',compact('data')); 
    }
    
    
}
    