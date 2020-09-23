<?php

namespace App\Controller;

use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\Client;
use App\Entity\User;
use App\Form\UserType;
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
use Swagger\Annotations as OA;

/**
 * Class ApiUserController
 * @package App\Controller
 * @Route("/api")
 */

class ApiUserController extends AbstractController
{

	/**
	 * Affiche les détails d'un utilisateur
	 *
	 * @Route("/users/{id}", name="api_users_details", methods={"GET"})
	 *
	 * @OA\Tag(name="Users")
	 * @OA\Response(
	 *     response=200,
	 *     description="Affiche les détails d'un utilisateur",
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
	public function details(UserRepository $userRepository, User $user)
	{
		$user = $userRepository->find($user->getId());

		return $this->json($user, 200, [], ['groups' => 'users:read']);

	}

	/**
	 * Modifie les détails d'un utilisateur
	 *
	 * @Route("/users/{id}", name="api_users_modify", methods={"PUT"})
	 *
	 * @OA\Tag(name="Users")
	 * @OA\Response(
	 *     response=200,
	 *     description="Affiche les détails d'un utilisateur",
	 * )
	 * @OA\Response(
	 *     response=201,
	 *     description="Modifie les détails d'un utilisateur",
	 * )
	 * @OA\Response(
	 *     response=400,
	 *     description="Les champs sont non conformes",
	 * )
	 * @OA\Response(
	 *     response=404,
	 *     description="La ressource n'a pas été trouvée (l'identifiant n'existe pas)",
	 * )
	 */
	public function modify(User $user, EntityManagerInterface $em, Request $request, SerializerInterface $serializer, ValidatorInterface $validator, SecurityController $securityController)
	{
		$jsonReceived = $request->getContent();

		if($jsonReceived) {
			$userModified = $serializer->deserialize($jsonReceived, User::class, 'json');
			if(is_null($userModified->getFirstname())) {
				$user->setFirstname(" ");
				$em->persist($user);
				$em->flush();
			}
			if($userModified && !is_null($userModified) && !is_null($userModified->getSurname()) && !is_null($userModified->getEmail())) {
					$client = $securityController->getUser();
					$user->setClient($client);
					$user->setSurname($userModified->getSurname());
					$user->setFirstname($userModified->getFirstname());
					$user->setEmail($userModified->getEmail());
					$validator->validate($user);
					$em->persist($user);
					$em->flush();
					return $this->json("L'utilisateur a bien été modifié", 201, [], ['groups' => 'users:modify']);
			} else {
				return $this->json("Erreur : l'utilisateur n'a pas pu être modifié. Veuillez vérifier la validité de vos champs. Champs requis : surname, firstname et email", 400, [], ['groups' => 'users:modify']);
			}
		}

		return $this->json($user, 200, [], ['groups' => 'users:modify']);
	}

    /**
	 * Liste l'ensemble des utilisateurs présents en BDD
	 *
     * @Route("/users", name="api_users_index", methods={"GET"})
	 *
	 * @OA\Tag(name="Users")
	 * @OA\Response(
	 *     response=200,
	 *     description="Liste l'ensemble des utilisateurs présents en BDD",
	 * )
     */
    public function index(UserRepository $userRepository, Request $request)
    {
		$page = $request->query->get('page');
		if(is_null($page) || $page < 1) {
			$page = 1;
		}
		$limit = 10;

		$client = $this->getUser();
		$clientID = $client->getId();

		$userList = $userRepository->findAllUsersByClient($page, $limit, $clientID);

		$query = $userList->getQuery();

		if(empty($query->getArrayResult())) {
			return $this->json("Cette page n'existe pas", 404);
		}

		return $this->json($userList, 200, [], ['groups' => 'users:read']);
    }

	/**
	 * Créée un nouvel utilisateur
	 *
	 * @Route("/users", name="api_users_create", methods={"POST"})
	 *
	 * @OA\Tag(name="Users")
	 * @OA\Response(
	 *     response=201,
	 *     description="Créée un nouvel utilisateur",
	 * )
	 * @OA\Response(
	 *     response=400,
	 *     description="Les champs sont non conformes ou l'email est déjà utilisé",
	 * )
	 */
    public function create(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, ValidatorInterface $validator, SecurityController $securityController)
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
	}

	/**
	 * Supprime un utilisateur
	 *
	 * @Route("/users/{id}", name="api_users_delete", methods={"DELETE"})
	 *
	 * @OA\Tag(name="Users")
	 * @OA\Response(
	 *     response=204,
	 *     description="Il n'y a plus de contenu lié à cet ID (l'utilisateur a bien été supprimé)",
	 * )
	 * @OA\Response(
	 *     response=404,
	 *     description="L'identifiant n'existe pas",
	 * )
	 */
	public function delete(User $user, EntityManagerInterface $em)
	{
		$em->remove($user);
		$em->flush();
		return new Response(null, 204);
	}
}
