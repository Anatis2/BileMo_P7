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
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
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
				return $this->json([
					'status' => 500,
					'message' => $errors
				], 500);
			}

			try {

				$em->persist($client);
				$em->flush();
				return $this->json([
					'status' => 201,
					'message' => 'Le client a bien été créé'
				], 201);
				return new JsonResponse($data, 201);
			} catch (NotEncodableValueException $e) {
				return $this->json([
					'status' => 400,
					'message' => "Erreur : vos données n'ont pas été envoyées. Veuillez vérifier la syntaxe de votre JSON."
				], 400);
			} catch (\Exception $e) {
				return $this->json([
					'status' => 400,
					'message' => "Erreur : il y a eu un problème lors de votre enregistrement."
				], 400);
			}
		}

		return $this->json([
			'status' => 500,
			'message' => 'Veuillez renseigner les champs email et password'
		], 500);
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
