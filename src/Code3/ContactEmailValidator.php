<?php

namespace BernardoSecades\Testing\Code3;

use Exception;

class ContactEmailValidator
{
    public static function create(): self
    {
        return new static();
    }

    /**
     * @param array $emails
     *
     * @throws Exception
     */
    public function check(array $emails): void
    {
        foreach ($emails as $email) {
            if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
                throw new Exception(sprintf('"%s" invalid email', $email));
            }

            $split = explode('@', $email);
            $domain = array_pop($split);

            if ($domain != 'escapadarural.com') {
                throw new Exception(sprintf('"%s" invalid email domain', $email));
            }
        }
    }
}
