<?php


namespace App\Service;


use App\Controller\SecurityController;
use App\Entity\Client;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ClientChecker extends AbstractController
{

	public function checkClient(UserRepository $userRepository, User $user)
	{
		$client = $this->getUser();
		$clientId = $client->getId();

		$usersList = $userRepository->findUsersByClient($clientId);

		foreach ($usersList as $k => $v) {
			if($user->getId() == $v->getId()) {
				return true;
			}
		}

	}

}