<?php

namespace Sevo\Model;

use Phalcon\Validation;
use Phalcon\Validation\Validator\Uniqueness;
use Phalcon\Validation\Validator\Email;

class Musteri extends Model {

    protected $ID;
    protected $adi;
    protected $soyad;
    protected $TC;
    protected $telefon;
    protected $email;
    protected $adres_id;
    protected $olusturulma_tarihi;
    protected $son_guncellenme_tarihi;

    public function validation()
    {
        $validator = new Validation();

        $validator->add(
            'email',
            new Email(
                [
                    'message' => 'E-posta adresi geçersiz!',
                ]
            )
        );
    }

    public function initialize()
    {
        $this->belongsTo(
            'adres_id',
            'Sevo\Model\Adres',
            'id',
            [
                'alias' => 'adres_id',
            ]
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
    public function getSoyad()
    {
        return $this->soyad;
    }

    /**
     * @param mixed $soyad
     */
    public function setSoyad($soyad): void
    {
        $this->soyad = $soyad;
    }

    /**
     * @return mixed
     */
    public function getTC()
    {
        return $this->TC;
    }

    /**
     * @param mixed $TC
     */
    public function setTC($TC): void
    {
        $this->TC = $TC;
    }

    /**
     * @return mixed
     */
    public function getTelefon()
    {
        return $this->telefon;
    }

    /**
     * @param mixed $telefon
     */
    public function setTelefon($telefon): void
    {
        $this->telefon = $telefon;
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
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getAdresİd()
    {
        return $this->adres_id;
    }

    /**
     * @param mixed $adres_id
     */
    public function setAdresİd($adres_id): void
    {
        $this->adres_id = $adres_id;
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