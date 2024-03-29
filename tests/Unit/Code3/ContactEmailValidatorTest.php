<?php

namespace Tests\Unit\Code3;

use BernardoSecades\Testing\Code3\ContactEmailValidator;
use PHPUnit\Framework\TestCase;
use Exception;

/**
 * @group code3
 */
class ContactEmailValidatorTest extends TestCase
{
    /** @var ContactEmailValidator */
    private $sut;

    protected function setUp()
    {
        parent::setUp();
        $this->sut = new ContactEmailValidator();
    }

    /**
     * @test
     * @throws Exception
     */
    public function wrong_format_email()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('"pepe@gmail" invalid email');

        $this->sut->check(['pepe@gmail']);
    }

    /**
     * @test
     * @throws Exception
     */
    public function right_format_email_but_domain_dont_allowed()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('"pepe@gmail.com" invalid email domain');

        $this->sut->check(['pepe@gmail.com']);
    }

    /**
     * @test
     * @throws Exception
     */
    public function valid_emails()
    {
        $this->expectNotToPerformAssertions();
        $this->sut->check(['pepe@escapadarural.com']);
    }
}
