<?php
namespace Sevo\Model;

use Phalcon\Security\Random;

class Model extends \Phalcon\Mvc\Model
{
    protected function generateUuid()
    {
        $random = new Random();
        $this->uuid = $random->uuid();
    }
}