<?php

namespace App\DataFixtures;

use App\Entity\Client;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ClientFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
		$clients = [
			[
				"name" => "Société test",
				"email" => "societe.test@gmail.com",
				"password" => "pwdTest",
			]
		];

		foreach($clients as $k => $v) {
			$client = new Client();

			$client
				   ->setName($v['name'])
				   ->setEmail($v['email'])
				   ->setPassword($v['password'])
			;

			$manager->persist($client);
		}

        $manager->flush();
    }
}
