<?php

namespace Tests\Unit\Code2;

use BernardoSecades\Testing\Code2\ContactEmailValidator;
use PHPUnit\Framework\TestCase;
use Exception;

/**
 * @group code2
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
