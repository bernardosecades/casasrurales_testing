<?php

namespace BernardoSecades\Testing\Code1;

use League\Csv\Exception;
use League\Csv\Reader;
use League\Csv\Writer;

class AddContactsUseCase
{
    /** @var string  */
    protected $basePath;

    public function __construct(string $basePath)
    {
        $this->basePath = $basePath;

        if (!ini_get("auto_detect_line_endings")) {
            ini_set("auto_detect_line_endings", '1');
        }
    }

    /**
     * @param array $contacts
     * @param int   $userId
     *
     * @throws Exception
     * @throws \League\Csv\CannotInsertRecord
     */
    public function execute(array $contacts, int $userId): void
    {
        // Check if user exist
        $csvReader = Reader::createFromString(file_get_contents($this->basePath . '/users.txt'));
        $csvReader->setDelimiter(',');

        $user = null;

        foreach ($csvReader->getRecords() as $record) {
            if ($userId == $record[0]) {
                $user = new User($record[0], $record[1], $record[2]);
                break;
            }
        }

        if (null === $user) {
            throw new Exception(sprintf('User with id: "%d" not found', $userId));
        }

        // Check premium user and number contacts to add to the same timne
        if ($user->getUserType() != 'premium' && count($contacts) > 2) {
            throw new Exception(sprintf('User no premium can not add more than 10 contacts to the same time', $userId));
        }

        // User is premium we have a limit of 5 contacts even for premium users
        if (count($contacts) > 5) {
            throw new Exception(sprintf('User can not add more than 5 contacts to the same time'));
        }

        // Validate $contacts have email in right format and right domain
        $csvWriter = Writer::createFromString('');
        $csvWriter->setDelimiter(',');

        foreach ($contacts as $email) {
            if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
                throw new Exception(sprintf('"%s" invalid email', $email));
            }

            $split = explode('@', $email);
            $domain = array_pop($split);

            if ($domain != 'escapadarural.com') {
                throw new Exception(sprintf('"%s" invalid email domain', $email));
            }

            $csvWriter->insertOne([$email]);
        }

        // Save contacts
        $saved = file_put_contents($this->basePath . '/user_' . $userId . '_contacts.txt' , $csvWriter->getContent());

        if ($saved === false) {
            throw new Exception('Error to try save contacts');
        }
    }
}
