<?php

namespace App\Controller;

use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\Client;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;


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

		return $this->json($user, 200, [], ['groups' => 'users:read']);

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

		return $this->json($userList, 200, [], ['groups' => 'users:read']);
    }

	/**
	 * @Route("/users", name="api_users_create", methods={"POST"})
	 */
    /*public function create(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, ValidatorInterface $validator, SecurityController $securityController)
	{
		$jsonReceived = $request->getContent();

		try {
			$user = $serializer->deserialize($jsonReceived, User::class, 'json');
			$user->setRegisteredAt(new \DateTime());

			$client = $securityController->getUser();
			$user->setClient($client);

			$validator->validate($user);
			$em->persist($user);
			$em->flush();
			return $this->json($user, 201, [], ['groups' => 'users:create']);
		} catch(NotEncodableValueException $e) {
			return $this->json([
				'status' => 400,
				'message' => "Erreur : vos données n'ont pas été envoyées. Veuillez vérifier la syntaxe de votre JSON."
			], 400);
		}
	}*/

	/**
	 * @Route("/users/{id}", name="api_users_delete", methods={"DELETE"})
	 */
	public function delete(User $user, EntityManagerInterface $em)
	{
		$em->remove($user);
		$em->flush();
		return new Response(null, 204);
	}
}
