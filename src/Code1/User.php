<?php

namespace BernardoSecades\Testing\Code1;

class User
{
    /**
     * @var int
     */
    private $userId;
    /**
     * @var string
     */
    private $email;
    /**
     * @var string
     */
    private $userType;

    /**
     * @param int    $userId
     * @param string $email
     * @param string $userType
     */
    public function __construct(int $userId, string $email, string $userType)
    {
        $this->userId = $userId;
        $this->email = $email;
        $this->userType = $userType;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getUserType(): string
    {
        return $this->userType;
    }
}
