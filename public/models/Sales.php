<?php
declare(strict_types=1);

namespace Sevo\Model;

use Phalcon\Security\Random;
class Sales extends Model
{
    protected $id;
    protected $created_at;
    protected $last_modified_at;
    protected $total;
    protected $patient_id;
    protected $uuid;

    public function beforeValidationOnCreate()
    {
        $this->generateUuid();
        $this->created_at = date('Y-m-d H:i:s');
    }

    public function beforeValidationOnUpdate()
    {
        $this->last_modified_at = date('Y-m-d H:i:s');
    }


    /** @var Patient $_Patient */

    public function initialize()
    {
        $this->belongsTo(
            'patient_id',
            'Sevo\Model\Patient',
            'id',
            [
                'alias' => '_Patient',
            ]
        );
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
     * @return Patient
     */
    public function getPatient()
    {
        return $this->_Patient;
    }

    /**
     * @return mixed
     */
    public function getPatientId()
    {
        return $this->patient_id;
    }

    /**
     * @param mixed $patient_id
     */
    public function setPatientId($patient_id)
    {
        $this->patient_id = $patient_id;
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
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @param mixed $total
     */
    public function setTotal($total)
    {
        $this->total = $total;
    }

}