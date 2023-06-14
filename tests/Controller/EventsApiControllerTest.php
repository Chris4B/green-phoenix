<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EventsApiControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $client->request('GET', '/api/events');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $responseData = json_decode($client->getResponse()->getContent(), true);
        // Add assertions to verify the expected data in the $responseData array
        $this->assertIsArray($responseData);

    }

    public function testNew()
    {
        $client = static::createClient();
        $client->request('POST', '/api/events/new', [], [], [], json_encode([
            'title' => 'Test Event',
            'datestr' => '2023-06-13'
        ]));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $responseData = json_decode($client->getResponse()->getContent(), true);
        // Add assertions to verify the expected data in the $responseData array
        $this->assertArrayHasKey('id', $responseData);
        $this->assertArrayHasKey('title', $responseData);
        $this->assertArrayHasKey('datestr', $responseData);

    }

    public function testUpdate()
    {
        $client = static::createClient();
        $client->request('POST', '/api/events/update/{id}', [], [], [], json_encode([
            'title' => 'Updated Event',
            'datestr' => '2023-06-14'
        ]));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $responseData = json_decode($client->getResponse()->getContent(), true);
        // Add assertions to verify the expected data in the $responseData array
        $this->assertArrayHasKey('title', $responseData);
        $this->assertArrayHasKey('datestr', $responseData);

    }

    public function testRemove()
    {
        $client = static::createClient();
        $client->request('DELETE', '/api/events/{id}');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $responseData = json_decode($client->getResponse()->getContent(), true);
        // Add assertions to verify the expected data in the $responseData array
        $this->assertIsArray($responseData);

    }
}
