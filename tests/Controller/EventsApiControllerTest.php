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

        foreach ($responseData as $event) {
            $this->assertArrayHasKey('id', $event);
            $this->assertArrayHasKey('title', $event);
            $this->assertArrayHasKey('datestr', $event);

            $this->assertNotEmpty($event['id']);
            $this->assertNotEmpty($event['title']);
            $this->assertNotEmpty($event['datestr']);

            // assertions based on the expected values of each event
            $this->assertIsInt($event['id']);
            $this->assertIsString($event['title']);
            $this->assertIsString($event['datestr']);
        }
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
        $this->assertArrayHasKey('id', $responseData);
        $this->assertArrayHasKey('title', $responseData);
        $this->assertArrayHasKey('datestr', $responseData);

        // Additional assertions based on the expected data
        $this->assertNotEmpty($responseData['id']);
        $this->assertEquals('Test Event', $responseData['title']);
        $this->assertEquals('2023-06-13', $responseData['datestr']);

        // Retrieve the created event from the API to verify its details
        $createdEventId = $responseData['id'];
        $client->request('GET', '/api/events/' . $createdEventId);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $createdEventData = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('id', $createdEventData);
        $this->assertArrayHasKey('title', $createdEventData);
        $this->assertArrayHasKey('datestr', $createdEventData);

        // Additional assertions to verify the details of the created event
        $this->assertEquals($createdEventId, $createdEventData['id']);
        $this->assertEquals('Test Event', $createdEventData['title']);
        $this->assertEquals('2023-06-13', $createdEventData['datestr']);


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
