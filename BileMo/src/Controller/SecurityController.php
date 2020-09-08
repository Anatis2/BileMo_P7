<?php

namespace App\Controller;

use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class SecurityController
 * @package App\Controller
 * @Route("/api")
 */
class SecurityController extends AbstractController
{
    /**
     * @Route("/register", name="api_clients_register", methods={"POST"})
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $em, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $values = json_decode($request->getContent());

        if(isset($values->email, $values->password)) {
        	$client = new Client();
        	$client->setEmail($values->email);
			$client->setPassword($passwordEncoder->encodePassword($client, $values->password));
			$client->setRoles($client->getRoles());
			$errors = $validator->validate($client);
			if(count($errors)) {
				$errors = $serializer->serialize($errors, 'json');
				return new Response($errors, 500, [
					'Content-Type' => 'application/json'
				]);
			}
        	$em->persist($client);
        	$em->flush();

			$data = [
				'status' => 201,
				'message' => 'Le client a bien été créé'
			];

			return new JsonResponse($data, 201);
		}

		$data = [
			'status' => 500,
			'message' => 'Veuillez renseigner les champs email et password'
		];
		return new JsonResponse($data, 500);
    }

	/**
	 * @Route("/login", name="api_clients_login", methods={"POST"})
	 */
    public function login()
	{
		$client = $this->getUser();

		return $this->json([
			'email' => is_object($client) ? $client->getUsername() : '',
			'roles' => is_object($client) ? $client->getRoles() : ''
		]);
	}
}
