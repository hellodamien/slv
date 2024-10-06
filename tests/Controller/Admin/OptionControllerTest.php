<?php

namespace App\Tests\Controller\Admin;

use App\Repository\CustomerRepository;
use App\Repository\OptionRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class OptionControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        $client = static::createClient();
        $customerRepository = static::getContainer()->get(CustomerRepository::class);

        $client->loginUser($customerRepository->findOneBy(['email' => 'admin@slv.local']));

        $client->request('GET', '/admin/option/');

        $this->assertResponseIsSuccessful();
    }

    public function testNew(): void
    {
        $client = static::createClient();
        $customerRepository = static::getContainer()->get(CustomerRepository::class);

        $client->loginUser($customerRepository->findOneBy(['email' => 'admin@slv.local']));

        $client->request('GET', '/admin/option/new');
        $formData = [
            'option[name]' => 'Test',
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
        $testItemId = static::getContainer()->get(OptionRepository::class)->findOneBy(['name' => 'Test'])->getId();

        $client->request('GET', '/admin/option/'.$testItemId.'/edit');
        $formData = [
            'option[name]' => 'Test',
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
        $testItemId = static::getContainer()->get(OptionRepository::class)->findOneBy(['name' => 'Test'])->getId();

        $client->request('GET', '/admin/option/'.$testItemId.'/delete');

        $this->assertNotEmpty($client->getCrawler()->filter('body'));
    }
}