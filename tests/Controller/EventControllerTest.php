<?php

namespace App\Tests\Controller;

use App\Entity\Event;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EventControllerTest extends WebTestCase
{
    private EntityManagerInterface $em;
    private $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();

        $this->em = self::getContainer()->get(EntityManagerInterface::class);

        $purger = new ORMPurger($this->em);
        $purger->purge();

        $this->loadEventFixtures();
    }

    private function loadEventFixtures(): void
    {
        $event = new Event();
        $event->setName('Test Event ' . uniqid("gg", true))
            ->setDate(new \DateTime('+1 day'))
            ->setLocation('Test Location')
            ->setAvailableSpots(5);

        $this->em->persist($event);
        $this->em->flush();
    }

    public function testEventIndexPage(): void
    {
        $this->client->request('GET', '/events');

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('h1', 'Available Events');
    }

    public function testEventRegistrationPage(): void
    {
        $event = $this->em->getRepository(Event::class)->findOneBy([]);

        $this->assertNotNull($event, 'No event found in the database.');

        $crawler = $this->client->request('GET', '/events/register/' . $event->getId());

        self::assertResponseIsSuccessful();

        $form = $crawler->selectButton('Register')->form([
            'event_registration[name]' => 'John Doe',
            'event_registration[email]' => 'john@example.com',
        ]);
        $this->client->submit($form);

        self::assertResponseRedirects('/events');
        $this->client->followRedirect();

        self::assertSelectorTextContains('.alert-success', 'You have successfully registered for the event!');
    }
}
