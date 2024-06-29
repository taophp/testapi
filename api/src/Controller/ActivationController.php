<?php
namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ActivationController extends AbstractController
{
    public function activate(string $token, UserRepository $userRepository, EntityManagerInterface $entityManager, Request $request): Response
    {

        $user = $userRepository->findOneBy(['activationToken' => $token]);

        if (!$user) {
            return $this->handleReponse($request, 'Invalid token', Response::HTTP_BAD_REQUEST);
        }

        $user->setActive(true);
        $user->setActivationToken(null);
        $entityManager->flush();

        return $this->handleReponse($request, 'User activated successfully!');
    }

    protected function handleReponse(Request $request, string $content, int $status = Response::HTTP_OK): Response {
        if ($request->headers->get('Accept') === 'application/json') {
            return new JsonResponse(['message' => $content], $status);
        }

        return new Response('<html><body><h1>'.$content.'</h1></body></html>', $status);
    }
}
