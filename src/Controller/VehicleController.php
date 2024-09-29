<?php

namespace App\Controller;

use App\Repository\VehicleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
class VehicleController extends AbstractController
{
    private VehicleRepository $vehicleRepository;

    public function __construct(VehicleRepository $vehicleRepository)
    {
        $this->vehicleRepository = $vehicleRepository;
    }

    #[Route('/vehicle/{id}', name: 'vehicle', requirements: ['id' => '\d+'])]
    public function index(int $id): Response
    {
        $vehicle = $this->vehicleRepository->find($id);

        if ($vehicle === null) {
            return $this->redirectToRoute('home');
        }

        $isAvailable = $this->vehicleRepository->isAvailable($id);

        return $this->render('vehicle/index.html.twig', [
            'vehicle' => $vehicle,
            'isAvailable' => $isAvailable,
        ]);
    }
}