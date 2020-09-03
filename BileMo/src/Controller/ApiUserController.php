<?php

namespace App\Controller;

use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class ApiUserController
 * @package App\Controller
 * @Route("/api")
 */

class ApiUserController extends AbstractController
{

	/**
	 * @Route("/users/{id}", name="api_users_details", methods={"GET"})
	 */
	public function details(UserRepository $userRepository, User $user)
	{
		$user = $userRepository->find($user->getId());

		return $this->json($user, 200, []);
	}

    /**
     * @Route("/users", name="api_users_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository, Request $request)
    {
		$page = $request->query->get('page');
		if(is_null($page) || $page < 1) {
			$page = 1;
		}
		$limit = 10;

		$userList = $userRepository->findAllUsers($page, $limit);

		return $this->json($userList, 200, []);
    }

	/**
	 * @Route("/users", name="api_users_create", methods={"POST"})
	 */
    public function create(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, ValidatorInterface $validator)
	{
		$jsonReceived = $request->getContent();

		try {
			$user = $serializer->deserialize($jsonReceived, User::class, 'json');
			$user->setRegisteredAt(new \DateTime());
			$validator->validate($user);
			$em->persist($user);
			$em->flush();
			return $this->json($user, 201, []);
		} catch(NotEncodableValueException $e) {
			return $this->json([
				'status' => 400,
				'message' => $e->getMessage()
			], 400);
		}


	}
}
