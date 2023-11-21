<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use App\Repository\VehiculeRepository;

class HomeController extends AbstractController
{
    /**
     * Page d'accueil
     * 
     * @return  Response
     */
    #[Route('/homepage', name: 'app_index')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', []);
    }
    public function contact(): Response
    {
        return $this->render('home/contact_horaires.html.twig', []);
    }
    #[Route('/contact/{id}', name: 'app_contact_id')]
    public function contactId(Request $request, VehiculeRepository $vehiculeRepository): Response
    {
        $id = $request->get('id');
        $vehicule = $vehiculeRepository->findOneBy(array('id' => $id));
        return $this->render('home/contact_horaires.html.twig', [
            'vehicule' => $vehicule
        ]);
    }
    #[Route('/horaires', name: 'app_horaires')]
    public function horaires(): Response
    {
        return $this->render('home/contact_horaires.html.twig', []);
    }
}
