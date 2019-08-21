<?php

namespace BernardoSecades\Testing\Code3;

use League\Csv\Exception;

interface UserRepository
{
    /**
     * @param int $userId
     *
     * @return User
     * @throws Exception
     */
    public function getUser(int $userId): ? User;

    /**
     * @param array $emails
     * @param int   $userId
     *
     * @throws Exception
     * @throws \League\Csv\CannotInsertRecord
     */
    public function saveUserContact(array $emails, int $userId): void;
}
