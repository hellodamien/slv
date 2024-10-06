<?php

namespace App\Tests\Controller;

use App\Repository\CustomerRepository;
use App\Repository\VehicleRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class VehicleControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        $client = static::createClient();
        $vehicleRepository = static::getContainer()->get(VehicleRepository::class);

        $vehicle = $vehicleRepository->findOneBy([]);
        $client->request('GET', '/vehicle/' . $vehicle->getId());

        $this->assertResponseIsSuccessful();
    }

    public function testRent(): void
    {
        $client = static::createClient();
        $vehicleRepository = static::getContainer()->get(VehicleRepository::class);

        $vehicle = $vehicleRepository->findOneBy([]);
        $client->request('GET', '/vehicle/' . $vehicle->getId() . '/reservation');

        $this->assertResponseRedirects('/login');
    }

    public function testRentWithAuthenticatedUser(): void
    {
        $client = static::createClient();
        $customerRepository = static::getContainer()->get(CustomerRepository::class);

        $client->loginUser($customerRepository->findOneBy([]));

        $vehicleRepository = static::getContainer()->get(VehicleRepository::class);
        $vehicle = $vehicleRepository->findOneBy([]);

        $client->request('GET', '/vehicle/' . $vehicle->getId() . '/reservation');
        $formData = [
            'reservation[startDate]' => (new DateTime())->format('Y-m-d'),
            'reservation[endDate]'   => (new DateTime())->modify('+1 day')->format('Y-m-d'),
        ];
        $form = $client->getCrawler()->selectButton('Réserver')->form();

        $client->submit($form, $formData);
        $this->assertResponseIsSuccessful();
    }
}