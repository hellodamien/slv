<?php

namespace App\Tests\Controller\Admin;

use App\Entity\Brand;
use App\Repository\BrandRepository;
use App\Repository\CustomerRepository;
use App\Repository\ModelRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ModelControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        $client = static::createClient();
        $customerRepository = static::getContainer()->get(CustomerRepository::class);

        $client->loginUser($customerRepository->findOneBy(['email' => 'admin@slv.local']));

        $client->request('GET', '/admin/model/');

        $this->assertResponseIsSuccessful();
    }

    public function testNew(): void
    {
        $client = static::createClient();
        $customerRepository = static::getContainer()->get(CustomerRepository::class);

        $client->loginUser($customerRepository->findOneBy(['email' => 'admin@slv.local']));

        $entityManager = static::getContainer()->get('doctrine')->getManager();

        $brand = new Brand();
        $brand->setName('Test');
        $entityManager->persist($brand);
        $entityManager->flush();

        $client->request('GET', '/admin/model/new');
        $formData = [
            'model[name]' => 'Test',
            'model[brand]' => $brand->getId(),
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
        $testItemId = static::getContainer()->get(ModelRepository::class)->findOneBy(['name' => 'Test'])->getId();

        $brandRepository = static::getContainer()->get(BrandRepository::class);

        $client->request('GET', '/admin/model/'.$testItemId.'/edit');
        $formData = [
            'model[name]' => 'Test',
            'model[brand]' => $brandRepository->findOneBy(['name' => 'Test'])->getId(),
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
        $testItemId = static::getContainer()->get(ModelRepository::class)->findOneBy(['name' => 'Test'])->getId();

        $client->request('GET', '/admin/model/'.$testItemId.'/delete');

        $this->assertNotEmpty($client->getCrawler()->filter('body'));
    }
}