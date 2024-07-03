<?php

namespace App\DataFixtures;

use App\Entity\Request;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;


class RequestFixtures extends Fixture
{
    public const REQUEST_REFERENCE_COUNT = 10;

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        for ($i = 0; $i < self::REQUEST_REFERENCE_COUNT; $i++) {
            $request = new Request();
            $request->setEmail($faker->email());
            $request->setFirstname($faker->firstName());
            $request->setLastname($faker->lastName());
            $request->setPhone($faker->phoneNumber());
            $request->setKbis("kbis.pdf");
            $request->setCreatedAt(new \DateTimeImmutable($faker->date()));
            $request->setUpdatedAt(new \DateTimeImmutable($faker->date()));
            $manager->persist($request);
        }

        $manager->flush();
    }
}
