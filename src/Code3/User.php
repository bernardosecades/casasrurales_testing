<?php

namespace BernardoSecades\Testing\Code3;

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
     * @param int      $userId
     * @param string   $email
     * @param UserType $userType
     */
    public function __construct(int $userId, string $email, UserType $userType)
    {
        $this->userId = $userId;
        $this->email = $email;
        $this->userType = $userType->getValue();
    }

    /**
     * @codeCoverageIgnore
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @codeCoverageIgnore
     * @return string
     */
    public function getEmail(): string
    {

        return $this->email;
    }

    /**
     * @codeCoverageIgnore
     * @return string
     */
    public function getUserType(): string
    {
        return $this->userType;
    }

    /**
     * @return bool
     */
    public function isPremiumUser(): bool
    {
        return UserType::PREMIUM == $this->userType;
    }

    /**
     * @return bool
     */
    public function isRegularUser(): bool
    {
        return UserType::REGULAR == $this->userType;
    }

    /**
     * @return int
     */
    public function getLimitContacts(): int
    {
        if ($this->isRegularUser()) {
            return 2;
        }

        return 5;
    }
}
