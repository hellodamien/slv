<?php

namespace App\Tests\Controller\Admin;

use App\Repository\BrandRepository;
use App\Repository\CustomerRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BrandControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        $client = static::createClient();
        $customerRepository = static::getContainer()->get(CustomerRepository::class);

        $client->loginUser($customerRepository->findOneBy(['email' => 'admin@slv.local']));

        $client->request('GET', '/admin/brand/');

        $this->assertResponseIsSuccessful();
    }

    public function testNew(): void
    {
        $client = static::createClient();
        $customerRepository = static::getContainer()->get(CustomerRepository::class);

        $client->loginUser($customerRepository->findOneBy(['email' => 'admin@slv.local']));

        $client->request('GET', '/admin/brand/new');
        $formData = [
            'brand[name]' => 'Test',
        ];

        $form = $client->getCrawler()->selectButton('Envoyer')->form();

        $client->submit($form, $formData);
        $this->assertNotEmpty($client->getCrawler()->filter('body'));
    }

    public function testEdit(): void
    {
        $client = static::createClient();
        $customerRepository = static::getContainer()->get(CustomerRepository::class);

        $client->loginUser($customerRepository->findOneBy(['email' => 'admin@slv.local']));
        $testItemId = static::getContainer()->get(BrandRepository::class)->findOneBy(['name' => 'Test'])->getId();

        $client->request('GET', '/admin/brand/'.$testItemId.'/edit');

        $this->assertNotEmpty($client->getCrawler()->filter('body'));
    }

    public function testDelete(): void
    {
        $client = static::createClient();
        $customerRepository = static::getContainer()->get(CustomerRepository::class);

        $client->loginUser($customerRepository->findOneBy(['email' => 'admin@slv.local']));
        $testItemId = static::getContainer()->get(BrandRepository::class)->findOneBy(['name' => 'Test'])->getId();

        $client->request('GET', '/admin/brand/'.$testItemId.'/delete');

        $this->assertNotEmpty($client->getCrawler()->filter('body'));
    }
}