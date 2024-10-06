<?php

namespace App\Tests\Controller;

use App\DTO\HomeVehicleSearch;
use App\Repository\TypeRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
    public function testHomePageIsUp()
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
    }

    public function testHomePageContent()
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $crawler = $client->getCrawler();

        $this->assertCount(6, $crawler->filter('.card'));
    }

    public function testHomePageContentWithSearch()
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $crawler = $client->getCrawler();
        $typeRepository = $this->getContainer()->get(TypeRepository::class);
        $formData = HomeVehicleSearch::create(
            new DateTime('2020-01-01'),
            new DateTime('2020-12-31'),
            $typeRepository->find(1),
            1,
        );

        $form = $crawler->filter('form')->form([
            'home_vehicle_search[startDate]' => $formData->startDate->format('Y-m-d'),
            'home_vehicle_search[endDate]'   => $formData->endDate->format('Y-m-d'),
            'home_vehicle_search[type]'      => $formData->type->getId(),
        ]);

        $client->submit($form);

        $this->assertSelectorExists('.card');
    }

    // @todo: fix pagination first, then bother with this
//    public function testHomePageContentWithSearchNoResult()
//    {
//        $client = static::createClient();
//        $client->request('GET', '/');
//
//        $crawler = $client->getCrawler();
//        $typeRepository = $this->getContainer()->get(TypeRepository::class);
//        $formData = HomeVehicleSearch::create(
//            new DateTime('1900-01-01'),
//            new DateTime('1900-12-31'),
//            $typeRepository->find(1),
//            1000,
//        );
//
//        $form = $crawler->filter('form')->form([
//            'home_vehicle_search[startDate]' => $formData->startDate->format('Y-m-d'),
//            'home_vehicle_search[endDate]' => $formData->endDate->format('Y-m-d'),
//            'home_vehicle_search[type]' => $formData->type->getId(),
//        ]);
//
//        $client->submit($form);
//
//        $this->assertSelectorNotExists('.card');
//    }
}