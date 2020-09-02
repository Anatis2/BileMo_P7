<?php

namespace App\DataFixtures;

use App\Entity\Phone;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PhoneFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
		$phones = [
			[	"brand" => "Samsung",
				"modelName" => "Galaxy2",
				"modelRef" => "",
				"OS" => "",
				"screenSize" => null,
				"memory" => null,
				"weight" => null,
				"description" => "",
				"releaseYear" => "2012",
				"price" => 200,00,
				"availability" => false,
			],
			[	"brand" => "Apple",
				"modelName" => "IPhone 2",
				"modelRef" => "",
				"OS" => "",
				"screenSize" => null,
				"memory" => null,
				"weight" => null,
				"description" => "",
				"releaseYear" => "2012",
				"price" => 350,00,
				"availability" => false,
			],
			[	"brand" => "Xiaomi",
				"modelName" => "Redmi Note 9",
				"modelRef" => "",
				"OS" => "",
				"screenSize" => null,
				"memory" => null,
				"weight" => null,
				"description" => "",
				"releaseYear" => "2019",
				"price" => 205,00,
				"availability" => true,
			],
			[	"brand" => "Nokia",
				"modelName" => "3310",
				"modelRef" => "",
				"OS" => "",
				"screenSize" => null,
				"memory" => null,
				"weight" => null,
				"description" => "",
				"releaseYear" => "2005",
				"price" => 48,00,
				"availability" => false,
			],
			[	"brand" => "Samsung",
				"modelName" => "Galaxy S10",
				"modelRef" => "",
				"OS" => "",
				"screenSize" => null,
				"memory" => null,
				"weight" => null,
				"description" => "",
				"releaseYear" => "2020",
				"price" => 149,00,
				"availability" => true,
			],
			[	"brand" => "Apple",
				"modelName" => "Iphone 11",
				"modelRef" => "",
				"OS" => "",
				"screenSize" => null,
				"memory" => null,
				"weight" => null,
				"description" => "",
				"releaseYear" => "2012",
				"price" => 350,00,
				"availability" => false,
			],
			[	"brand" => "Huawei",
				"modelName" => "P20",
				"modelRef" => "",
				"OS" => "Android",
				"screenSize" => "5",
				"memory" => "128",
				"weight" => "150",
				"description" => "",
				"releaseYear" => "2020",
				"price" => 549,00,
				"availability" => true,
			],
			[	"brand" => "Archos",
				"modelName" => "Diamond Omega",
				"modelRef" => "",
				"OS" => "Android",
				"screenSize" => "5",
				"memory" => "128",
				"weight" => "150",
				"description" => "",
				"releaseYear" => "2020",
				"price" => 549,00,
				"availability" => true,
			],
			[	"brand" => "Honor",
				"modelName" => "9",
				"modelRef" => "",
				"OS" => "Android",
				"screenSize" => "5",
				"memory" => "128",
				"weight" => "150",
				"description" => "",
				"releaseYear" => "2020",
				"price" => 400,00,
				"availability" => true,
			],
			[	"brand" => "Blackberry",
				"modelName" => "Curve 9320",
				"modelRef" => "",
				"OS" => "BlackberryOs",
				"screenSize" => "7",
				"memory" => "512",
				"weight" => "120",
				"description" => "",
				"releaseYear" => "2020",
				"price" => 114,00,
				"availability" => true,
			],
			[	"brand" => "Samsung",
				"modelName" => "S9",
				"modelRef" => "",
				"OS" => "Android",
				"screenSize" => "5",
				"memory" => "128",
				"weight" => "150",
				"description" => "",
				"releaseYear" => "118",
				"price" => 549,00,
				"availability" => true,
			],
			[	"brand" => "Samsung",
				"modelName" => "S8",
				"modelRef" => "",
				"OS" => "Android",
				"screenSize" => "5",
				"memory" => "128",
				"weight" => "150",
				"description" => "",
				"releaseYear" => "2018",
				"price" => 349,00,
				"availability" => true,
			],
			[	"brand" => "Apple",
				"modelName" => "Iphone 5",
				"modelRef" => "",
				"OS" => "IOS",
				"screenSize" => "5",
				"memory" => "128",
				"weight" => "150",
				"description" => "",
				"releaseYear" => "2020",
				"price" => 350,00,
				"availability" => true,
			],
			[	"brand" => "Huawei",
				"modelName" => "Modèle RR",
				"modelRef" => "",
				"OS" => "Android",
				"screenSize" => "5",
				"memory" => "128",
				"weight" => "150",
				"description" => "",
				"releaseYear" => "2020",
				"price" => 500,00,
				"availability" => true,
			],
			[	"brand" => "Huawei",
				"modelName" => "Modèle DD",
				"modelRef" => "",
				"OS" => "Android",
				"screenSize" => "12",
				"memory" => "1750",
				"weight" => "99",
				"description" => "",
				"releaseYear" => "2020",
				"price" => 750,00,
				"availability" => true,
			],
		];

		foreach($phones as $k => $v) {
			$phone = new Phone();

			$phone
				->setBrand($v['brand'])
				->setModelName($v['modelName'])
				->setReleaseYear($v['releaseYear'])
				->setModelRef($v['modelRef'])
				->setOS($v['OS'])
				->setScreenSize($v['screenSize'])
				->setWeight($v['weight'])
				->setDescription($v['description'])
				->setMemory(($v['memory']))
				->setPrice($v['price'])
				->setAvailability($v['availability']);

			$manager->persist($phone);
		}

		$manager->flush();
    }
}