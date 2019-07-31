<?php

namespace Sevo\Model;

use Phalcon\Validation;
use Phalcon\Validation\Validator\Uniqueness;

class Marka extends Model {

    protected $ID;
    protected $adi;
    protected $marka_kodu;
    protected $marka_logo;
    protected $olusturulma_tarihi;
    protected $son_guncellenme_tarihi;

    public function validation()
    {
        $validator = new Validation();

        $validator->add(
            'marka_kodu',
            new Uniqueness(
                [
                    'message' => 'marka kodu benzersiz olmalıdır.',
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
    public function getMarkaKodu()
    {
        return $this->marka_kodu;
    }

    /**
     * @param mixed $marka_kodu
     */
    public function setMarkaKodu($marka_kodu): void
    {
        $this->marka_kodu = $marka_kodu;
    }

    /**
     * @return mixed
     */
    public function getMarkaLogo()
    {
        return $this->marka_logo;
    }

    /**
     * @param mixed $marka_logo
     */
    public function setMarkaLogo($marka_logo): void
    {
        $this->marka_logo = $marka_logo;
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