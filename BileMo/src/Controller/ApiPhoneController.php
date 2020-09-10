<?php

namespace App\Controller;

use App\Entity\Phone;
use App\Repository\PhoneRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use JMS\Serializer\SerializationContext;

/**
 * Class ApiPhoneController
 * @package App\Controller
 * @Route("/api")
 */
class ApiPhoneController extends AbstractController
{

	/**
	 * @Route("/phones/{id}", name="api_phones_details", methods={"GET"})
	 */
    public function details(PhoneRepository $phoneRepository, Phone $phone, \JMS\Serializer\SerializerInterface $serializer)
	{
		$phone = $phoneRepository->find($phone->getId());

		$json = $serializer->serialize($phone, 'json');

		$response = new Response($json);
		$response->headers->set('Content-Type', 'application/json');

		return $response;
	}

	/**
	 * @Route("/phones/{page<\d+>?1}", name="api_phones_index", methods={"GET"})
	 */
	public function index(PhoneRepository $phoneRepository, Request $request)
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
