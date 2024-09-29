<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\StatusRepository;
use App\Repository\VehicleRepository;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
class VehicleController extends AbstractController
{
    private VehicleRepository      $vehicleRepository;
    private StatusRepository       $statusRepository;
    private EntityManagerInterface $entityManager;

    public function __construct
    (
        VehicleRepository      $vehicleRepository,
        StatusRepository       $statusRepository,
        EntityManagerInterface $entityManager
    )
    {
        $this->vehicleRepository = $vehicleRepository;
        $this->statusRepository  = $statusRepository;
        $this->entityManager     = $entityManager;
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
    public function rent(Request $request, int $id): Response
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

        $form = $this->createForm(ReservationType::class, $reservation);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($reservation);
            $this->entityManager->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('vehicle/reservation.html.twig', [
            'vehicle' => $vehicle,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/api/vehicle/{id}/availability?start={start}&end={end}',
        name: 'api_vehicle_availability',
        requirements: [
            'id' => '\d+',
            'start' => '\d+',
            'end' => '\d+'
        ]
    )]
    public function getVehicleAvailability(int $id, int $start, int $end): JsonResponse
    {
        $startDate = new DateTime();
        $startDate->setTimestamp($start);

        $endDate = new DateTime();
        $endDate->setTimestamp($end);

        $isAvailable = $this->vehicleRepository->isAvailableDuringTimeFrame($id, $startDate, $endDate);

        return new JsonResponse(json_encode($isAvailable));
    }
}