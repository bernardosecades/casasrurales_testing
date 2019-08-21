<?php

namespace BernardoSecades\Testing\Code3;

use League\Csv\Exception;
use League\Csv\Reader;
use League\Csv\Writer;

class UserFileSystemRepository implements UserRepository
{
    /** @var string  */
    protected $basePath;

    public function __construct(string $basePath)
    {
        $this->basePath = $basePath;

        if (!ini_get("auto_detect_line_endings")) {
            // @codeCoverageIgnoreStart
            ini_set("auto_detect_line_endings", '1');
            // @codeCoverageIgnoreEnd
        }
    }

    /**
     * @param int $userId
     *
     * @return User
     * @throws Exception
     */
    public function getUser(int $userId): ? User
    {
        $csvReader = Reader::createFromString(file_get_contents($this->basePath . '/users.csv'));
        $csvReader->setDelimiter(',');

        $user = null;

        foreach ($csvReader->getRecords() as $record) {
            if ($userId == $record[0]) {
                $user = new User($record[0], $record[1], new UserType($record[2]));
                break;
            }
        }

        return $user;
    }

    /**
     * @param array $emails
     * @param int   $userId
     *
     * @throws Exception
     * @throws \League\Csv\CannotInsertRecord
     */
    public function saveUserContact(array $emails, int $userId): void
    {
        $csvWriter = Writer::createFromString('');
        $csvWriter->setDelimiter(',');

        foreach ($emails as $email) {
            $csvWriter->insertOne([$email]);
        }

        $saved = file_put_contents($this->basePath . '/user_' . $userId . '_contacts.csv' , $csvWriter->getContent());

        if ($saved === false) {
            throw new Exception('Error to try save contacts');
        }
    }
}
