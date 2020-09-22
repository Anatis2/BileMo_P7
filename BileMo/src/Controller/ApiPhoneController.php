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
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Swagger\Annotations as OA;

/**
 * Class ApiPhoneController
 * @package App\Controller
 * @Route("/api")
 */
class ApiPhoneController extends AbstractController
{

	/**
	 * Affiche les détails d'un téléphone
	 *
	 * @Route("/phones/{id}", name="api_phones_details", methods={"GET"})
	 *
	 * @OA\Tag(name="Phones")
	 * @OA\Response(
	 *     response=200,
	 *     description="Affiche les détails d'un téléphone",
	 * )
	 * @OA\Response(
	 *     response=404,
	 *     description="L'identifiant n'existe pas",
	 * )
	 * @OA\Response(
	 *     response=401,
	 *     description="Le token est invalide, a expiré, ou n'est pas renseigné",
	 * )
	 */
    public function details(PhoneRepository $phoneRepository, Phone $phone)
	{
		$phone = $phoneRepository->find($phone->getId());

		return $this->json($phone, 200, []);
	}

	/**
	 * Liste l'ensemble des téléphones présents dans le catalogue
	 *
	 * @Route("/phones/{page<\d+>?1}", name="api_phones_index", methods={"GET"})
	 *
	 * @OA\Tag(name="Phones")
	 * @OA\Response(
	 *     response=200,
	 *     description="Liste l'ensemble des téléphones présents dans le catalogue",
	 * )
	 */
	public function index(PhoneRepository $phoneRepository, Request $request)
	{
		$page = $request->query->get('page');
		if(is_null($page) || $page < 1) {
			$page = 1;
		}
		$limit = 10;

		$phoneList = $phoneRepository->findAllPhones($page, $limit);

		$query = $phoneList->getQuery();

		if(empty($query->getArrayResult())) {
			return $this->json("Cette page n'existe pas", 404);
		}

		return $this->json($phoneList, 200, []);
	}



}
