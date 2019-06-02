<?php

namespace App\Security;

use App\Exception\InvalidConfirmationTokenException;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserConfirmationService
{
    /**
     * @var UserRepository
     */
    private $repository;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(UserRepository $repository, EntityManagerInterface $entityManager)
    {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
    }

    public function confirmUser(string $confirmationToken)
    {
        $user = $this->repository->findOneBy(['confirmationToken' => $confirmationToken]);

        // User was found by confirmation token
        if (!$user) {
            throw new InvalidConfirmationTokenException();
        }

        $user->setEnabled(true);
        $user->setConfirmationToken(null);
        $this->entityManager->flush();
    }
}
