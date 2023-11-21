<?php

namespace App\Controller;

use App\Entity\Service;
use App\Form\ServiceType;
use App\Repository\ServiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/service')]
class ServiceController extends AbstractController
{
    #[Route('/', name: 'app_service_index', methods: ['GET'])]
    public function index(ServiceRepository $serviceRepository): Response
    {
        return $this->render('service/index.html.twig', [
            'services' => $serviceRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_service_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $service = new Service();
        return $this->edit($request, $service, $entityManager);
    }

    #[Route('/{id}', name: 'app_service_show', methods: ['GET'])]
    public function show(Service $service): Response
    {
        return $this->render('service/show.html.twig', [
            'service' => $service,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_service_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Service $service, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        $newService = $service->getId() <= 0;
        $form = $this->createForm(ServiceType::class, $service, []);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                //Suppression de l'image précédente si elle existe déjà
                $fileName = $service->getPictureName();
                if ($fileName) {
                    $currentImage = $this->getParameter('service_pictures_directory') . "/" . $fileName;
                    if (file_exists($currentImage)) {
                        unlink($currentImage);
                    }
                }

                $newFilename = 'service-' . uniqid() . '.' . $imageFile->guessExtension();
                try {
                    $imageFile->move(
                        $this->getParameter('service_pictures_directory'),
                        $newFilename
                    );
                    $service->setPictureName($newFilename);
                } catch (FileException) {
                }
            }

            if ($newService)
                $entityManager->persist($service);
            $entityManager->flush();

            return $this->redirectToRoute('app_service_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('helpers/global/_edit.html.twig', [
            'mode' => $newService ? 'new' : 'edit',
            'data' => $service,
            'form' => $form,
            'destination' => 'service',
            'title' => 'service'
        ]);
    }

    #[Route('/{id}', name: 'app_service_delete', methods: ['POST'])]
    public function delete(Request $request, Service $service, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        if ($this->isCsrfTokenValid('delete' . $service->getId(), $request->request->get('_token'))) {

            //Suppression de l'image service
            $fileName = $service->getPictureName();
            if ($fileName) {
                $currentImage = $this->getParameter('service_pictures_directory') . "/" . $fileName;
                if (file_exists($currentImage)) {
                    unlink($currentImage);
                }
            }

            $entityManager->remove($service);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_service_index', [], Response::HTTP_SEE_OTHER);
    }
}
