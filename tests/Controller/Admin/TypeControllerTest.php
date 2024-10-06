<?php

namespace App\Tests\Controller\Admin;

use App\Repository\CustomerRepository;
use App\Repository\TypeRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TypeControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        $client = static::createClient();
        $customerRepository = static::getContainer()->get(CustomerRepository::class);

        $client->loginUser($customerRepository->findOneBy(['email' => 'admin@slv.local']));

        $client->request('GET', '/admin/type/');

        $this->assertResponseIsSuccessful();
    }

    public function testNew(): void
    {
        $client = static::createClient();
        $customerRepository = static::getContainer()->get(CustomerRepository::class);

        $client->loginUser($customerRepository->findOneBy(['email' => 'admin@slv.local']));

        $client->request('GET', '/admin/type/new');
        $formData = [
            'type[name]' => 'Test',
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
        $testItemId = static::getContainer()->get(TypeRepository::class)->findOneBy(['name' => 'Test'])->getId();

        $client->request('GET', '/admin/type/'.$testItemId.'/edit');
        $formData = [
            'type[name]' => 'Test',
        ];

        $form = $client->getCrawler()->selectButton('Envoyer')->form();

        $client->submit($form, $formData);
        $this->assertNotEmpty($client->getCrawler()->filter('body'));
    }

    public function testDelete(): void
    {
        $client = static::createClient();
        $customerRepository = static::getContainer()->get(CustomerRepository::class);

        $client->loginUser($customerRepository->findOneBy(['email' => 'admin@slv.local']));
        $testItemId = static::getContainer()->get(TypeRepository::class)->findOneBy(['name' => 'Test'])->getId();

        $client->request('GET', '/admin/type/'.$testItemId.'/delete');

        $this->assertNotEmpty($client->getCrawler()->filter('body'));
    }
}