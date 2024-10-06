<?php

namespace App\Tests\Controller\Admin;

use App\Repository\CustomerRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        $client = static::createClient();
        $customerRepository = static::getContainer()->get(CustomerRepository::class);

        $client->loginUser($customerRepository->findOneBy(['email' => 'admin@slv.local']));

        $client->request('GET', '/admin/');

        $this->assertNotEmpty($client->getCrawler()->filter('body'));
    }
}