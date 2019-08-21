<?php

namespace BernardoSecades\Testing\Code3;

use League\Csv\Exception;

class AddContactsUseCase
{
    /**
     * @var UserFileSystemRepository
     */
    private $userRepository;

    /**
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param array $contacts
     * @param int   $userId
     *
     * @throws Exception
     * @throws \League\Csv\CannotInsertRecord
     */
    public function execute(array $contacts, int $userId): void
    {
        $user = $this->userRepository->getUser($userId);

        if (!$user) {
            throw new Exception(sprintf('User with id: "%d" not found', $userId));
        }

        if (count($contacts) > $user->getLimitContacts()) {
            throw new Exception(
                sprintf('User type: "%s" can not add more than %d contacts',
                    $user->getUserType(),
                    $user->getLimitContacts()
                )
            );
        }

        ContactEmailValidator::create()->check($contacts);

        $this->userRepository->saveUserContact($contacts, $userId);
    }
}
