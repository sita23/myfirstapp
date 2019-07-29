<?php
namespace Sevo\Model;

use Phalcon\Security\Random;
class Sgk extends Model
{
    protected $id;
    protected $name;
    protected $uuid;

    public function beforeValidationOnCreate()
    {
        $this->generateUuid();
    }

    public function initialize()
    {
        $this->hasMany(
            'id',
            'Patient',
            'sgk_id'
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
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
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