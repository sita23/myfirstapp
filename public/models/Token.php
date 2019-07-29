<?php
declare(strict_types=1);

namespace Sevo\Model;

use Phalcon\Validation;
use Phalcon\Validation\Validator\Uniqueness;
use Phalcon\Validation\Validator\InclusionIn;

class Token extends Model
{

    protected $id;
    protected $user_id;
    protected $token;
    protected $expiration_date;
    protected $status;
    protected $created_at;
    protected $uuid;

    public function initialize()
{
    $this->hasMany(
        'id',
        'user_id',
        'token_id'
    );

    $this->belongsTo(
        'user_id',
        'Sevo\Model\User',
        'id',
        [
            'alias' => 'User',
        ]
    );
}

    public function validation()
    {
        $validator = new Validation();

        $validator->add(
            'token',
            new Uniqueness(
                [
                    'message' => 'Kullanıcının tokenı benzersiz olmalı.',
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
    public function setCreatedAt($created_at): void
    {
        $this->created_at = $created_at;
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
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @param mixed $user_id
     */
    public function setUserId($user_id): void
    {
        $this->user_id = $user_id;
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param mixed $token
     */
    public function setToken($token): void
    {
        $this->token = $token;
    }

    /**
     * @return mixed
     */
    public function getExpirationDate()
    {
        return $this->expiration_date;
    }

    /**
     * @param mixed $expiration_date
     */
    public function setExpirationDate($expiration_date): void
    {
        $this->expiration_date = $expiration_date;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status): void
    {
        $this->status = $status;
    }

}
