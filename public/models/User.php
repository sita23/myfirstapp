<?php

namespace Sevo\Model;

use Phalcon\Validation;
use Phalcon\Validation\Validator\Uniqueness;
use Phalcon\Validation\Validator\InclusionIn;
use Phalcon\Validation\Validator\Email;
use Phalcon\Security\Random;

class User extends Model
{
    protected $id;

    protected $user_name;

    protected $password;

    protected $email;

    protected $created_at;

    protected $last_modified_at;

    protected $uuid;

    public function validation()
    {
        $validator = new Validation();

        $validator->add(
            'user_name',
            new Uniqueness(
                [
                    'message' => 'Kullanıcı adı benzersiz olmalı.',
                ]
            )
        );

        $validator->add(
            'email',
            new Email(
                [
                    'message' => 'E-posta adresi geçersiz!',
                ]
            )
        );

        return $this->validate($validator);
    }

    public function beforeValidationOnCreate()
    {
        $this->created_at = date('Y-m-d H:i:s');
        $this->generateUuid();
    }

    public function beforeValidationOnUpdate()
    {
        $this->last_modified_at = date('Y-m-d H:i:s');
    }

    public function getSource()
    {
        return 'user';
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getUserName()
    {
        return $this->user_name;
    }

    /**
     * @param mixed $user_name
     */
    public function setUserName($user_name)
    {
        $this->user_name = $user_name;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @param mixed $created_at
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }

    /**
     * @return mixed
     */
    public function getLastModifiedAt()
    {
        return $this->last_modified_at;
    }

    /**
     * @param mixed $last_modified_at
     */
    public function setLastModifiedAt($last_modified_at)
    {
        $this->last_modified_at = $last_modified_at;
    }

    /**
     * @return mixed
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * @param mixed $uuid
     */
    public function setUuid($uuid): void
    {
        $this->uuid = $uuid;
    }

}