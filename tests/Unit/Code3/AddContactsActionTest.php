<?php

namespace Tests\Unit\Code3;

use BernardoSecades\Testing\Code3\AddContactsUseCase;
use BernardoSecades\Testing\Code3\User;
use BernardoSecades\Testing\Code3\UserRepository;
use BernardoSecades\Testing\Code3\UserType;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Faker\Factory as FakerFactory;
use Faker\Generator;
use Exception;

class AddContactsActionTest extends TestCase
{
    /** @var ObjectProphecy|UserRepository */
    private $userFileSystemRepository;

    /** @var int */
    private $userId;

    /** @var Generator */
    private $faker;

    /** @var AddContactsUseCase */
    private $sut;

    protected function setUp()
    {
        parent::setUp();

        $this->faker = FakerFactory::create();
        $this->userId = $this->faker->randomNumber();
        $this->userFileSystemRepository = $this->prophesize(UserRepository::class);

        $this->sut = new AddContactsUseCase($this->userFileSystemRepository->reveal());
    }

    /**
     * @test
     */
    public function user_can_not_be_found()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("User with id: \"{$this->userId}\" not found");

        $this->userFileSystemRepository
            ->getUser($this->userId)
            ->shouldBeCalledOnce()
            ->willReturn(null);

        $contacts = [
            'adri@escapadarural.com',
            'elena@escapadarural.com',
        ];

        $this->sut->execute($contacts, $this->userId);
    }

    /**
     * @test
     */
    public function regular_user_can_not_add_contacts_by_limit()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('User type: "regular" can not add more than 2 contacts');

        $user = new User($this->userId, $this->faker->email, UserType::REGULAR());

        $this->userFileSystemRepository
            ->getUser($this->userId)
            ->shouldBeCalledOnce()
            ->willReturn($user);

        $contacts = [
            'adri@escapadarural.com',
            'elena@escapadarural.com',
            'jacinto@escapadarural.com',
        ];

        $this->sut->execute($contacts, $this->userId);
    }

    /**
     * @test
     */
    public function premium_user_can_not_add_contacts_by_limit()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('User type: "premium" can not add more than 5 contacts');

        $user = new User($this->userId, $this->faker->email, UserType::PREMIUM());

        $this->userFileSystemRepository
            ->getUser($this->userId)
            ->shouldBeCalledOnce()
            ->willReturn($user);

        $contacts = [
            'adri@escapadarural.com',
            'elena@escapadarural.com',
            'jacinto@escapadarural.com',
            'jacinto1@escapadarural.com',
            'jacinto2@escapadarural.com',
            'jacinto3@escapadarural.com',
        ];

        $this->sut->execute($contacts, $this->userId);
    }

    /**
     * TODO Note: In some context this test it is not make sense, only if we handle custom exceptions or something like that
     * @test
     */
    public function user_try_to_add_contacts_with_wrong_domain()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("\"elena@google.com\" invalid email domain");

        $user = new User($this->userId, $this->faker->email, UserType::REGULAR());

        $this->userFileSystemRepository
            ->getUser($this->userId)
            ->shouldBeCalledOnce()
            ->willReturn($user);

        $contacts = [
            'adri@escapadarural.com',
            'elena@google.com',
        ];

        $this->sut->execute($contacts, $this->userId);
    }

    /**
     * @test
     */
    public function user_add_contacts_success()
    {
        $user = new User($this->userId, $this->faker->email, UserType::REGULAR());

        $this->userFileSystemRepository
            ->getUser($this->userId)
            ->shouldBeCalledOnce()
            ->willReturn($user);

        $contacts = [
            'adri@escapadarural.com',
            'elena@escapadarural.com',
        ];

        $this->userFileSystemRepository->saveUserContact($contacts, $this->userId)->shouldBeCalledOnce();

        $this->sut->execute($contacts, $this->userId);
    }
}
