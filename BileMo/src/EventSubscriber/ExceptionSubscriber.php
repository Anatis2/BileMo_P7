<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

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

        /*if($exception instanceof BadRequestHttpException) {
			$data = [
				'status' => $exception->getStatusCode(),
				'message' => 'Erreur : vos données n\'ont pas été envoyées. Veuillez vérifier la syntaxe de votre JSON.'
			];

			$response = new JsonResponse($data);
			$event->setResponse($response);
		}*/

    }

    public static function getSubscribedEvents()
    {
        return [
            'kernel.exception' => 'onKernelException',
        ];
    }
}
