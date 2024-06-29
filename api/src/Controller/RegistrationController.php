<?php

namespace App\Controller;

use App\Dto\UserRegistration;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationController extends AbstractController
{
    public function register(
        Request $request,
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher,
        MailerInterface $mailer
    ): JsonResponse {
        $data = $request->getContent();
        $userRegistration = $serializer->deserialize($data, UserRegistration::class, 'json');
        $errors = $validator->validate($userRegistration);

        if (count($errors) > 0) {
            return $this->json($errors, Response::HTTP_BAD_REQUEST);
        }

        $user = new User();
        $user->setEmail($userRegistration->email);
        $user->setPassword($passwordHasher->hashPassword($user, $userRegistration->password));
        $user->setActive(false);
        $user->setActivationToken(bin2hex(random_bytes(32)));

        $entityManager->persist($user);
        $entityManager->flush();

        $this->sendActivationEmail($user, $mailer);

        return $this->json(['message' => 'User registered successfully!'], Response::HTTP_CREATED);
    }

    private function sendActivationEmail(User $user, MailerInterface $mailer)
    {

        $email = (new Email())
            ->from('no-reply@testapi.com')
            ->to($user->getEmail())
            ->subject('Please confirm your email')
            ->html(sprintf('Please click <a href="%s/users/activate/%s">here</a> to activate your account.', $_ENV['APP_URL'], $user->getActivationToken()));

        $mailer->send($email);
    }
}
