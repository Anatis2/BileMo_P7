<?php

namespace App\Controller;

use App\Entity\Phone;
use App\Repository\PhoneRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class ApiPhoneController extends AbstractController
{


	/**
	 * @Route("/api/phones/{id}", name="api_phones_details", methods={"GET"})
	 */
    public function details(PhoneRepository $phoneRepository, Phone $phone)
	{
		$phone = $phoneRepository->find($phone->getId());

		return $this->json($phone, 200, []);
	}

	/**
	 * @Route("/api/phones/{page<\d+>?1}", name="api_phones_index", methods={"GET"})
	 */
	public function index(PhoneRepository $phoneRepository, Request $request, SerializerInterface $serializer)
	{
		$page = $request->query->get('page');
		if(is_null($page) || $page < 1) {
			$page = 1;
		}
		$limit = 10;

		$phoneList = $phoneRepository->findAllPhones($page, $limit);

		return $this->json($phoneList, 200, []);

	}

}
