<?php

namespace Sevo\Model;

class Siparis_Ozet extends Model {

    protected $ID;
    protected $musteri_id;
    protected $urun_id;
    protected $fiyat;
    protected $adet;
    protected $olusturulma_tarihi;
    protected $son_guncellenme_tarihi;

    public function initialize()
    {
        $this->belongsTo(
            'musteri_id',
            'Sevo\Model\Musteri',
            'id',
            [
                'alias' => 'musteri_id',
            ]
        );

        $this->belongsTo(
            'urun_id',
            'Sevo\Model\Urun',
            'id',
            [
                'alias' => 'urun_id',
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
    public function getMusteriİd()
    {
        return $this->musteri_id;
    }

    /**
     * @param mixed $musteri_id
     */
    public function setMusteriİd($musteri_id): void
    {
        $this->musteri_id = $musteri_id;
    }

    /**
     * @return mixed
     */
    public function getUrunİd()
    {
        return $this->urun_id;
    }

    /**
     * @param mixed $urun_id
     */
    public function setUrunİd($urun_id): void
    {
        $this->urun_id = $urun_id;
    }

    /**
     * @return mixed
     */
    public function getFiyat()
    {
        return $this->fiyat;
    }

    /**
     * @param mixed $fiyat
     */
    public function setFiyat($fiyat): void
    {
        $this->fiyat = $fiyat;
    }

    /**
     * @return mixed
     */
    public function getAdet()
    {
        return $this->adet;
    }

    /**
     * @param mixed $adet
     */
    public function setAdet($adet): void
    {
        $this->adet = $adet;
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