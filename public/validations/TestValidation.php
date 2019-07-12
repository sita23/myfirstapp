<?php

namespace Sevo\Validation;

use Phalcon\Validation;
use Phalcon\Validation\Validator\Date;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\PresenceOf;

class TestValidation extends Validation
{
    public function initialize()
    {

        $this->add(
            'email',
            new PresenceOf(
                [
                    'message' => 'The e-mail is required',
                ]
            )
        );

        $this->add(
            'email',
            new Email(
                [
                    'message' => 'The e-mail is not valid',
                ]
            )
        );
    }
}
