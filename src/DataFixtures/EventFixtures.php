<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Event;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class EventFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $eventWithSpots = new Event();
        $eventWithSpots->setName('Test Event With Spots')
            ->setDate(new \DateTime('+1 day'))
            ->setLocation('Test Location')
            ->setAvailableSpots(10);

        $eventWithoutSpots = new Event();
        $eventWithoutSpots->setName('Test Event Without Spots')
            ->setDate(new \DateTime('+1 day'))
            ->setLocation('Test Location')
            ->setAvailableSpots(0);

        $manager->persist($eventWithSpots);
        $manager->persist($eventWithoutSpots);

        $manager->flush();
    }
}
