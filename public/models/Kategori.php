<?php

namespace Sevo\Model;

use Phalcon\Validation;
use Phalcon\Validation\Validator\Uniqueness;

class Kategori extends Model {

    protected $ID;
    protected $adi;
    protected $olusturulma_tarihi;
    protected $son_guncellenme_tarihi;

    public function validation()
    {
        $validator = new Validation();

        $validator->add(
            'adi',
            new Uniqueness(
                [
                    'message' => 'kategori adı benzersiz olmalıdır.',
                ]
            )
        );
    }

    public function beforeValidationOnCreate()
    {
        $this->olusturulma_tarihi = date('Y-m-d H:i:s');
    }

    public function beforeValidationOnUpdate()
    {
        $this->son_guncellenme_tarihi = date('Y-m-d H:i:s');
    }

    /**
     * @return mixed
     */
    public function getID()
    {
        return $this->ID;
    }

    /**
     * @param mixed $ID
     */
    public function setID($ID): void
    {
        $this->ID = $ID;
    }

    /**
     * @return mixed
     */
    public function getAdi()
    {
        return $this->adi;
    }

    /**
     * @param mixed $adi
     */
    public function setAdi($adi): void
    {
        $this->adi = $adi;
    }

    /**
     * @return mixed
     */
    public function getOlusturulmaTarihi()
    {
        return $this->olusturulma_tarihi;
    }

    /**
     * @param mixed $olusturulma_tarihi
     */
    public function setOlusturulmaTarihi($olusturulma_tarihi): void
    {
        $this->olusturulma_tarihi = $olusturulma_tarihi;
    }

    /**
     * @return mixed
     */
    public function getSonGuncellenmeTarihi()
    {
        return $this->son_guncellenme_tarihi;
    }

    /**
     * @param mixed $son_guncellenme_tarihi
     */
    public function setSonGuncellenmeTarihi($son_guncellenme_tarihi): void
    {
        $this->son_guncellenme_tarihi = $son_guncellenme_tarihi;
    }



}