<?php

namespace App\Tests\Controller\Admin;

use App\Repository\CustomerRepository;
use App\Repository\ModelRepository;
use App\Repository\OptionRepository;
use App\Repository\TypeRepository;
use App\Repository\VehicleRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class VehicleControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        $client = static::createClient();
        $customerRepository = static::getContainer()->get(CustomerRepository::class);

        $client->loginUser($customerRepository->findOneBy(['email' => 'admin@slv.local']));

        $client->request('GET', '/admin/vehicle/');

        $this->assertResponseIsSuccessful();
    }

    public function testNew(): void
    {
        $client = static::createClient();
        $customerRepository = static::getContainer()->get(CustomerRepository::class);

        $client->loginUser($customerRepository->findOneBy(['email' => 'admin@slv.local']));

        $typeRepository = static::getContainer()->get(TypeRepository::class);
        $modelRepository = static::getContainer()->get(ModelRepository::class);
        $optionRepository = static::getContainer()->get(OptionRepository::class);

        $client->request('GET', '/admin/vehicle/new');
        $formData = [
            'vehicle[type]'           => $typeRepository->findOneBy([])->getId(),
            'vehicle[model]'          => $modelRepository->findOneBy([])->getId(),
            'vehicle[passengers]'     => 1,
            'vehicle[dailyRent]'      => 1,
            'vehicle[odometer]'       => 1,
            'vehicle[licensePlate]'   => 'x-000-x',
            'vehicle[productionYear]' => 1900,
            'vehicle[picture]'        => 'Test',
            'vehicle[options]'        => $optionRepository->findOneBy([])->getId(),
        ];

        $form = $client->getCrawler()->selectButton('Enregistrer')->form();

        $client->submit($form, $formData);
        $this->assertNotEmpty($client->getCrawler()->filter('body'));
    }

    public function testEdit(): void
    {
        $client = static::createClient();
        $customerRepository = static::getContainer()->get(CustomerRepository::class);

        $client->loginUser($customerRepository->findOneBy(['email' => 'admin@slv.local']));
        $testItemId = static::getContainer()->get(VehicleRepository::class)->findOneBy(['productionYear' => '1900'])->getId();

        $client->request('GET', '/admin/vehicle/'.$testItemId.'/edit');
        $formData = [
            'vehicle[dailyRent]' => 3,
        ];

        $form = $client->getCrawler()->selectButton('Enregistrer')->form();

        $client->submit($form, $formData);
        $this->assertNotEmpty($client->getCrawler()->filter('body'));
    }

    public function testDelete(): void
    {
        $client = static::createClient();
        $customerRepository = static::getContainer()->get(CustomerRepository::class);

        $client->loginUser($customerRepository->findOneBy(['email' => 'admin@slv.local']));
        $testItemId = static::getContainer()->get(VehicleRepository::class)->findOneBy(['productionYear' => '1900'])->getId();

        $client->request('GET', '/admin/vehicle/'.$testItemId.'/delete');

        $this->assertNotEmpty($client->getCrawler()->filter('body'));
    }
}