<?php

namespace Sevo\Model;

use Phalcon\Validation;
use Phalcon\Validation\Validator\Uniqueness;
use Phalcon\Validation\Validator\InclusionIn;
use Phalcon\Security\Random;

class Patient extends Model
{
    protected $id;
    protected $tc;
    protected $surname;
    protected $address;
    protected $sgk_id;
    protected $uuid;

    public function beforeValidationOnCreate()
    {
        $this->generateUuid();
    }


    public function validation()
    {
        $validator = new Validation();

        $validator->add(
            'tc',
            new Uniqueness(
                [
                    'message' => 'tc kimlik benzersiz olmalı.',
                ]
            )
        );
        return $this->validate($validator);
    }

    public function initialize()
    {
        $this->hasMany(
            'id',
            'Sales',
            'patient_id'
        );

        $this->belongsTo(
            'sgk_id',
            'Sevo\Model\Sgk',
            'id',
            [
                'alias' => '_Sgk',
            ]
        );
    }

    /**
     * @return Sgk
     */
    public function getSgk()
    {
        return $this->_Sgk;
    }


    /**
     * @return mixed
     */
    public function getSgkId()
    {
        return $this->sgk_id;
    }

    /**
     * @param mixed $sgk_id
     */
    public function setSgkId($sgk_id): void
    {
        $this->sgk_id = $sgk_id;
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
    public function getTc()
    {
        return $this->tc;
    }

    /**
     * @param mixed $tc
     */
    public function setTc($tc)
    {
        $this->tc = $tc;
    }


    /**
     * @return mixed
     */
    public function getSurName()
    {
        return $this->surname;
    }

    /**
     * @param mixed $surname
     */
    public function setSurName($surname)
    {
        $this->surname = $surname;
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
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

}