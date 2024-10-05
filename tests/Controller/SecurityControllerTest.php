<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    public function testLoginPageIsUp()
    {
        $client = static::createClient();
        $client->request('GET', '/login');

        $this->assertResponseIsSuccessful();
    }

    public function testLoginPageContent()
    {
        $client = static::createClient();
        $client->request('GET', '/login');

        $crawler = $client->getCrawler();

        $this->assertCount(1, $crawler->filter('form'));
    }

    public function testRegistrationPageIsUp()
    {
        $client = static::createClient();
        $client->request('GET', '/register');

        $this->assertResponseIsSuccessful();
    }

    public function testRegistrationPageContent()
    {
        $client = static::createClient();
        $client->request('GET', '/register');

        $crawler = $client->getCrawler();

        $this->assertCount(1, $crawler->filter('form'));
    }
}