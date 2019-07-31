<?php

namespace Sevo\Model;

class Adres extends Model {

    protected $ID;
    protected $sehir;
    protected $il;
    protected $ilce;
    protected $aciklama;
    protected $olusturulma_tarihi;
    protected $son_guncellenme_tarihi;

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
    public function getSehir()
    {
        return $this->sehir;
    }

    /**
     * @param mixed $sehir
     */
    public function setSehir($sehir): void
    {
        $this->sehir = $sehir;
    }

    /**
     * @return mixed
     */
    public function getil()
    {
        return $this->il;
    }

    /**
     * @param mixed $il
     */
    public function setil($il): void
    {
        $this->il = $il;
    }

    /**
     * @return mixed
     */
    public function getilce()
    {
        return $this->ilce;
    }

    /**
     * @param mixed $ilce
     */
    public function setilce($ilce): void
    {
        $this->ilce = $ilce;
    }

    /**
     * @return mixed
     */
    public function getAciklama()
    {
        return $this->aciklama;
    }

    /**
     * @param mixed $aciklama
     */
    public function setAciklama($aciklama): void
    {
        $this->aciklama = $aciklama;
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