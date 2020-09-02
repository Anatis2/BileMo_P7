<?php

namespace App\Controller;

use App\Repository\PhoneRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ApiPhoneController extends AbstractController
{
    /**
     * @Route("/api/phones/{page<\d+>?1}", name="api_phones_index", methods={"GET"})
     */
    public function index(PhoneRepository $phoneRepository, Request $request)
    {
    	$page = $request->query->get('page');

    	if(is_null($page) || $page < 1) {
    		$page = 1;
		}

    	$limit = 10;

        return $this->json($phoneRepository->findAllPhones($page, $limit), 200, []);
    }

	/**
	 * @Route("/api/phones/{id}", name="api_phones_details", methods={"GET"})
	 */
    public function details(PhoneRepository $phoneRepository, $id)
	{
		return $this->json($phoneRepository->find($id), 200);
	}

}
