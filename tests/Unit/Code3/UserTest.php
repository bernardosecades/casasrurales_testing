<?php

namespace Tests\Unit\Code3;

use BernardoSecades\Testing\Code3\User;
use BernardoSecades\Testing\Code3\UserType;
use PHPUnit\Framework\TestCase;
use Faker\Factory as FakerFactory;
use Faker\Generator;

/**
 * @group code3
 */
class UserTest extends TestCase
{
    /** @var Generator */
    private $faker;

    protected function setUp()
    {
        parent::setUp();
        $this->faker = FakerFactory::create();
    }

    /**
     * @test
     */
    public function limit_contact_for_regular_user()
    {
        $user = new User($this->faker->randomNumber(), $this->faker->email, UserType::REGULAR());
        $this->assertEquals(2, $user->getLimitContacts());
        $this->assertTrue($user->isRegularUser());
    }

    /**
     * @test
     */
    public function limit_contact_for_premium_user()
    {
        $user = new User($this->faker->randomNumber(), $this->faker->email, UserType::PREMIUM());
        $this->assertEquals(5, $user->getLimitContacts());
        $this->assertTrue($user->isPremiumUser());
    }
}
