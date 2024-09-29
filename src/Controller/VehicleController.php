<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Repository\StatusRepository;
use App\Repository\VehicleRepository;
use DateTime;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
class VehicleController extends AbstractController
{
    private VehicleRepository $vehicleRepository;
    private StatusRepository  $statusRepository;

    public function __construct
    (
        VehicleRepository $vehicleRepository,
        StatusRepository  $statusRepository
    )
    {
        $this->vehicleRepository = $vehicleRepository;
        $this->statusRepository  = $statusRepository;
    }

    #[Route('/vehicle/{id}', name: 'vehicle', requirements: ['id' => '\d+'])]
    public function index(int $id): Response
    {
        $vehicle = $this->vehicleRepository->find($id);

        if (null === $vehicle) {
            return $this->redirectToRoute('home');
        }

        $isAvailable = $this->vehicleRepository->isAvailable($id);

        return $this->render('vehicle/index.html.twig', [
            'vehicle' => $vehicle,
            'isAvailable' => $isAvailable,
        ]);
    }

    #[Route('/vehicle/{id}/reservation', name: 'reservation', requirements: ['id' => '\d+'])]
    public function rent(int $id): Response
    {
        $vehicle = $this->vehicleRepository->find($id);

        if (null === $vehicle) {
            return $this->redirectToRoute('home');
        }

        $customer = $this->getUser();

        $reservation = new Reservation();
        $reservation->setVehicle($vehicle);
        $reservation->setCustomer($customer);
        $reservation->setStatus($this->statusRepository->find(1));
        $reservation->setReference(uniqid());
        $reservation->setStartDate(new DateTimeImmutable());
        $reservation->setEndDate((new DateTimeImmutable())->modify('+1 day'));

        return new Response('ain\'t finished sowwy');
    }

    #[Route('/api/vehicle/{id}/availability?start={startDate}&end={endDate}',
        name: 'api_vehicle_availability',
        requirements: [
            'id' => '\d+',
            'startDate' => '\d{4}-\d{2}-\d{2}',
            'endDate' => '\d{4}-\d{2}-\d{2}'
        ]
    )]
    public function getVehicleAvailability(int $id, string $startDate, string $endDate): JsonResponse
    {
        $isAvailable = $this->vehicleRepository->isAvailableDuringTimeFrame(
            $id,
            DateTime::createFromFormat('Y-m-d', $startDate),
            DateTime::createFromFormat('Y-m-d', $endDate)
        );

        return new JsonResponse(json_encode($isAvailable));
    }
}