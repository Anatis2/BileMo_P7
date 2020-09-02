<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
	public function load(ObjectManager $manager)
    {
		$faker = \Faker\Factory::create('fr_FR');

		for($i = 1 ; $i <= 15 ; $i++) {
			$user = new User();
			$user->setSurname($faker->name)
				 ->setFirstname($faker->firstName)
				 ->setEmail($faker->email)
				 ->setRegisteredAt(new \DateTime());

			$manager->persist($user);
		}

        $manager->flush();
    }
}
