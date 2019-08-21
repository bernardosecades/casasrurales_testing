<?php

namespace Tests\Integration\Code1;

use BernardoSecades\Testing\Code1\AddContactsUseCase;
use PHPUnit\Framework\TestCase;

/**
 * @group code1
 */
class AddContactsUseCaseTest extends TestCase
{
    /** @var string */
    private $contactFile;

    /** @var string */
    private $basePath;

    /** @var int */
    private $userId;

    /** @var AddContactsUseCase */
    private $sut;

    protected function setUp()
    {
        parent::setUp();
        $this->userId = 1;
        $this->basePath = dirname(__DIR__, 1) . '/data';
        $this->contactFile = $this->basePath . "/user_{$this->userId}_contacts.txt";

        $this->sut = new AddContactsUseCase($this->basePath);
    }

    protected function tearDown()
    {
        parent::tearDown();
        if (file_exists($this->contactFile)) {
            unlink($this->contactFile);
        }
    }

    /**
     * @test
     * @throws \League\Csv\CannotInsertRecord
     * @throws \League\Csv\Exception
     */
    public function save_contancts_for_regular_user()
    {
        $contacts = [
            'adri@escapadarural.com',
            'elena@escapadarural.com',
        ];

        $this->sut->execute($contacts, $this->userId);

        $this->assertFileExists($this->contactFile);

        $contentFile = <<<EOF
adri@escapadarural.com
elena@escapadarural.com

EOF;

        $this->assertEquals($contentFile, file_get_contents($this->contactFile));
    }
}
