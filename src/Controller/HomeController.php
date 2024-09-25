<?php

namespace App\Controller;

use App\DTO\HomeVehicleSearch;
use App\Entity\Type;
use App\Form\HomeVehicleSearchType;
use App\Repository\VehicleRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
class HomeController extends AbstractController
{
    private VehicleRepository $vehicleRepository;

    public function __construct
    (
        VehicleRepository $vehicleRepository
    )
    {
        $this->vehicleRepository = $vehicleRepository;
    }

    #[Route('/', name: 'home')]
    public function index(Request $request): Response
    {
        $dto = HomeVehicleSearch::create(
            new DateTime('today'),
            new DateTime('tomorrow'),
            new Type(),
        );

        $form = $this->createForm(HomeVehicleSearchType::class, $dto);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $vehicles = $this->vehicleRepository->findAvailable($dto);
        } else {
            $vehicles = $this->vehicleRepository->findMostReserved();
        }

        return $this->render('home/index.html.twig', [
            'form' => $form->createView(),
            'vehicles' => $vehicles,
        ]);
    }
}