<?php

namespace App\Controller;

use App\Repository\PhoneRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ApiPhoneController extends AbstractController
{
    /**
     * @Route("/api/phones", name="api_phones_index", methods={"GET"})
     */
    public function index(PhoneRepository $phoneRepository)
    {
        return $this->json($phoneRepository->findAll(), 200, []);
    }

	/**
	 * @Route("/api/phones/{id}", name="api_phones_details", methods={"GET"})
	 */
    public function details(PhoneRepository $phoneRepository, $id)
	{
		return $this->json($phoneRepository->find($id), 200);
	}

}
