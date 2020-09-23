<?php

namespace App\Controller;

use App\Entity\Client;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Swagger\Annotations as OA;

/**
 * Class SecurityController
 * @package App\Controller
 * @Route("/api")
 */
class SecurityController extends AbstractController
{

	/**
	 * Affiche les détails du client (fournisseur) connecté
	 *
	 * @Route("/clients", name="api_clients_details", methods={"GET"})
	 *
	 * @OA\Tag(name="Clients")
	 * @OA\Response(
	 *     response=200,
	 *     description="Affiche les détails du client (fournisseur) connecté",
	 * )
	 */
	public function showClient()
	{
		$client = $this->getUser();

		if($client) {
			return $this->json($client, 200, [], ['groups' => 'clients:read']);
		}

	}

    /**
	 * Enregistre un client (fournisseur) dans la BDD
	 *
     * @Route("/register", name="api_clients_register", methods={"POST"})
	 *
	 * @OA\Tag(name="Clients")
	 * @OA\Response(
	 *     response=200,
	 *     description="L'inscription a bien été prise en compte",
	 * )
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $em, ValidatorInterface $validator)
    {
        $values = json_decode($request->getContent());

        if(isset($values->email, $values->password)) {
        	$client = new Client();
        	$client->setEmail($values->email);
        	$client->setPassword($values->password);
			$errors = $validator->validate($client);
			if(count($errors)) {
				foreach ($errors as $error) {
					return $this->json([
						'status' => 500,
						'message' => $error->getMessage()
					], 500);
				}
			}
			$client->setPassword($passwordEncoder->encodePassword($client, $values->password));
			$client->setRoles($client->getRoles());

			try {
				$em->persist($client);
				$em->flush();
				return $this->json([
					'status' => 201,
					'message' => 'Le client a bien été créé'
				], 201);
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
	 * Authentifie le client (fournisseur)
	 *
	 * @Route("/login_check", name="api_clients_login", methods={"POST"})
	 *
	 * @OA\Tag(name="Clients")
	 * @OA\Response(
	 *     response=200,
	 *     description="L'authentification s'est bien passée",
	 * )
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
