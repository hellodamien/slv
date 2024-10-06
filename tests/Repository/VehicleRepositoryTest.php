<?php

namespace App\Tests\Repository;

use App\DTO\HomeVehicleSearch;
use App\Entity\Type;
use App\Repository\TypeRepository;
use App\Repository\VehicleRepository;
use DateTime;
use PHPUnit\Framework\TestCase;

class VehicleRepositoryTest extends TestCase
{
    public function testIsAvailable(): void
    {
        $vehicleRepository = $this->createMock(VehicleRepository::class);

        $vehicleRepository
            ->expects($this->any())
            ->method('isAvailable')
            ->willReturn(true)
        ;

        $this->assertNotEmpty($vehicleRepository->isAvailable(1));
    }

    public function testIsAvailableDuringTimeFrame(): void
    {
        $vehicleRepository = $this->createMock(VehicleRepository::class);

        $vehicleRepository
            ->expects($this->any())
            ->method('isAvailableDuringTimeFrame')
            ->willReturn(true)
        ;

        $this->assertNotEmpty($vehicleRepository->isAvailableDuringTimeFrame(1, new DateTime('now'), new DateTime('tomorrow')));
    }

    public function testFindMostReserved(): void
    {
        $vehicleRepository = $this->createMock(VehicleRepository::class);

        $vehicleRepository
            ->expects($this->any())
            ->method('findMostReserved')
            ->willReturn([])
        ;

        $this->assertEmpty($vehicleRepository->findMostReserved(1, 1));
    }

    public function testFindAvailable(): void
    {
        $vehicleRepository = $this->createMock(VehicleRepository::class);

        $vehicleRepository
            ->expects($this->any())
            ->method('findAvailable')
            ->willReturn([])
        ;

        $typeRepository = $this->createMock(TypeRepository::class);

        $typeRepository
            ->expects($this->any())
            ->method('find')
            ->willReturn(new Type())
        ;

        $dto = HomeVehicleSearch::create(
            new DateTime('now'),
            new DateTime('tomorrow'),
            $typeRepository->find(1),
            1
        );

        $this->assertEmpty($vehicleRepository->findAvailable($dto));
    }

    public function testGetAvailableCount(): void
    {
        $vehicleRepository = $this->createMock(VehicleRepository::class);

        $vehicleRepository
            ->expects($this->any())
            ->method('getAvailableCount')
            ->willReturn(1)
        ;

        $typeRepository = $this->createMock(TypeRepository::class);

        $typeRepository
            ->expects($this->any())
            ->method('find')
            ->willReturn(new Type());

        $dto = HomeVehicleSearch::create(
            new DateTime('now'),
            new DateTime('tomorrow'),
            $typeRepository->find(1),
            1
        );

        $this->assertNotEmpty($vehicleRepository->getAvailableCount($dto));
    }
}