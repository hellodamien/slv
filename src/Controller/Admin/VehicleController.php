<?php

namespace App\Controller\Admin;

use App\Entity\Vehicle;
use App\Form\VehicleType;
use App\Repository\VehicleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/vehicle')]
class VehicleController extends AbstractController
{
    private VehicleRepository      $vehicleRepository;
    private EntityManagerInterface $entityManager;

    public function __construct
    (
        VehicleRepository      $vehicleRepository,
        EntityManagerInterface $entityManager
    )
    {
        $this->vehicleRepository = $vehicleRepository;
        $this->entityManager     = $entityManager;
    }

    #[Route('/', name: 'admin_vehicle_index')]
    public function index(): Response
    {
        $vehicles = $this->vehicleRepository->findAll();

        return $this->render('admin/vehicle/index.html.twig', [
            'vehicles' => $vehicles,
        ]);
    }

    #[Route('/{id}', name: 'admin_vehicle_show', requirements: ['id' => '\d+'])]
    public function show(int $id): Response
    {
        $vehicle = $this->vehicleRepository->find($id);

        return $this->render('admin/vehicle/show.html.twig', [
            'vehicle' => $vehicle,
        ]);
    }

    #[Route('/new', name: 'admin_vehicle_new')]
    public function new(Request $request): Response
    {
        $vehicle = new Vehicle();

        $form = $this->createForm(VehicleType::class, $vehicle);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($vehicle);
            $this->entityManager->flush();

            return $this->redirectToRoute('admin_vehicle_index');
        }

        return $this->render('admin/vehicle/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_vehicle_edit', requirements: ['id' => '\d+'])]
    public function edit(Request $request, int $id): Response
    {
        $vehicle = $this->vehicleRepository->find($id);

        $form = $this->createForm(VehicleType::class, $vehicle);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('admin_vehicle_index');
        }

        return $this->render('admin/vehicle/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/delete', name: 'admin_vehicle_delete', requirements: ['id' => '\d+'])]
    public function delete(int $id): Response
    {
        $vehicle = $this->vehicleRepository->find($id);

        $this->entityManager->remove($vehicle);
        $this->entityManager->flush();

        return $this->redirectToRoute('admin_vehicle_index');
    }
}