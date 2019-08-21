<?php

namespace Tests\Unit\Code2;

use BernardoSecades\Testing\Code2\AddContactsUseCase;
use BernardoSecades\Testing\Code2\User;
use BernardoSecades\Testing\Code2\UserFileSystemRepository;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Faker\Factory as FakerFactory;
use Faker\Generator;
use Exception;

/**
 * @group code2
 */
class AddContactsActionTest extends TestCase
{
    /** @var ObjectProphecy|UserFileSystemRepository */
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
        $this->userFileSystemRepository = $this->prophesize(UserFileSystemRepository::class);

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
        $this->expectExceptionMessage("User no premium can not add more than 2 contacts to the same time");

        /** @var ObjectProphecy|User $user */
        $user = $this->prophesize(User::class);
        $user->getUserType()->willReturn('regular');

        $this->userFileSystemRepository
            ->getUser($this->userId)
            ->shouldBeCalledOnce()
            ->willReturn($user->reveal());

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
        $this->expectExceptionMessage("User can not add more than 5 contacts to the same time");

        /** @var ObjectProphecy|User $user */
        $user = $this->prophesize(User::class);
        $user->getUserType()->willReturn('premium');

        $this->userFileSystemRepository
            ->getUser($this->userId)
            ->shouldBeCalledOnce()
            ->willReturn($user->reveal());

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

        /** @var ObjectProphecy|User $user */
        $user = $this->prophesize(User::class);
        $user->getUserType()->willReturn('regular');

        $this->userFileSystemRepository
            ->getUser($this->userId)
            ->shouldBeCalledOnce()
            ->willReturn($user->reveal());

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
        /** @var ObjectProphecy|User $user */
        $user = $this->prophesize(User::class);
        $user->getUserType()->willReturn('regular');

        $this->userFileSystemRepository
            ->getUser($this->userId)
            ->shouldBeCalledOnce()
            ->willReturn($user->reveal());

        $contacts = [
            'adri@escapadarural.com',
            'elena@escapadarural.com',
        ];

        $this->userFileSystemRepository->saveUserContact($contacts, $this->userId)->shouldBeCalledOnce();

        $this->sut->execute($contacts, $this->userId);
    }
}
