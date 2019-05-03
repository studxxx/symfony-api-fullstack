<?php

namespace App\Controller;

use App\Security\UserConfirmationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DefaultController
 * @package Controller
 * @Route("/")
 */
class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="default_index")
     */
    public function index()
    {
        return new JsonResponse([
            'action' => 'index',
            'time' => time()
        ]);
    }

    /**
     * @Route("confirm-user/{token}", name="default_confirm_token")
     */
    public function confirmUser(string $token, UserConfirmationService $confirmationService)
    {
        $confirmationService->confirmUser($token);

        return $this->redirectToRoute('default_index');
    }
}
