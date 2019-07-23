<?php
declare(strict_types=1);

namespace Sevo\Model;

use Phalcon\Mvc\Model;

class Token extends Model
{
    protected $id;
    protected $user_id;
    protected $token;
    protected $expiration_date;
    protected $status;

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
            'alias' => '_User',
        ]
    );
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
