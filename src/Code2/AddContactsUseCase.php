<?php

namespace BernardoSecades\Testing\Code2;

use League\Csv\Exception;

class AddContactsUseCase
{
    /**
     * @var UserFileSystemRepository
     */
    private $userFileSystemRepository;

    /**
     * @param UserFileSystemRepository $userFileSystemRepository
     */
    public function __construct(UserFileSystemRepository $userFileSystemRepository)
    {
        $this->userFileSystemRepository = $userFileSystemRepository;
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
        $user = $this->userFileSystemRepository->getUser($userId);

        if (!$user) {
            throw new Exception(sprintf('User with id: "%d" not found', $userId));
        }

        if ($user->getUserType() != 'premium' && count($contacts) > 2) {
            throw new Exception(sprintf('User no premium can not add more than 2 contacts to the same time', $userId));
        }

        if (count($contacts) > 5) {
            throw new Exception(sprintf('User can not add more than 5 contacts to the same time'));
        }

        ContactEmailValidator::create()->check($contacts);

        $this->userFileSystemRepository->saveUserContact($contacts, $userId);
    }
}
