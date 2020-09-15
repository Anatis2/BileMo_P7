<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Doctrine\ORM\NonUniqueResultException;

class ExceptionSubscriber implements EventSubscriberInterface
{
    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();

        if($exception instanceof NotFoundHttpException) {
			$data = [
				'status' => $exception->getStatusCode(),
				'message' => 'Cette route n\'existe pas, ou n\'a pas été trouvée'
			];

			$response = new JsonResponse($data);
			$event->setResponse($response);
		}

        if($exception instanceof AccessDeniedHttpException) {
			$data = [
				'status' => $exception->getStatusCode(),
				'message' => 'Vous n\'êtes pas autorisé à venir ici'
			];

			$response = new JsonResponse($data);
			$event->setResponse($response);
		}

		if($exception instanceof NonUniqueResultException) {
			$data = [
				'status' => $exception->getStatusCode(),
				'message' => 'Cet email est déjà utilisé'
			];

			$response = new JsonResponse($data);
			$event->setResponse($response);
		}

		if($exception instanceof BadRequestHttpException) {
			$data = [
				'status' => $exception->getStatusCode(),
				'message' => 'Les champs username et password sont requis'
			];

			$response = new JsonResponse($data);
			$event->setResponse($response);
		}

    }

    public static function getSubscribedEvents()
    {
        return [
            'kernel.exception' => 'onKernelException',
        ];
    }
}
