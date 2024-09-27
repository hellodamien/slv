<?php

namespace App\Controller;

use App\DTO\HomeVehicleSearch;
use App\Entity\Type;
use App\Form\HomeVehicleSearchType;
use App\Repository\ModelRepository;
use App\Repository\TypeRepository;
use App\Repository\VehicleRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
class HomeController extends AbstractController
{
    public const ITEMS_PER_PAGE = 6;
    
    private VehicleRepository $vehicleRepository;
    private TypeRepository    $typeRepository;
    private ModelRepository   $modelRepository;

    public function __construct
    (
        VehicleRepository $vehicleRepository,
        TypeRepository    $typeRepository,
        ModelRepository   $modelRepository
    )
    {
        $this->vehicleRepository = $vehicleRepository;
        $this->typeRepository    = $typeRepository;
        $this->modelRepository   = $modelRepository;
    }

    #[Route('/', name: 'home')]
    #[Route('/?page={page}', name: 'home_paginated')]
    public function index(Request $request, int $page = 0): Response
    {
        $dto = HomeVehicleSearch::create(
            new DateTime('today'),
            new DateTime('tomorrow'),
            $this->typeRepository->findAll()[0],
            $page,
            self::ITEMS_PER_PAGE
        );

        $form = $this->createForm(HomeVehicleSearchType::class, $dto);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $vehicles = $this->vehicleRepository->findAvailable($dto);
            $hasUsedFilter = true;
        } else {
            $vehicles = $this->vehicleRepository->findMostReserved(self::ITEMS_PER_PAGE, $page);
            $hasUsedFilter = false;
        }
        foreach ($vehicles as $id => $vehicle) {
            $vehicle['type'] = $this->typeRepository->find($vehicle['type_id']);
            $vehicle['model'] = $this->modelRepository->find($vehicle['model_id']);
            $vehicles[$id] = $vehicle;
        }

        return $this->render('home/index.html.twig', [
            'form'          => $form->createView(),
            'vehicles'      => $vehicles,
            'hasUsedFilter' => $hasUsedFilter,
        ]);
    }
}