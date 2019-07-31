<?php

namespace Sevo\Model;

use Phalcon\Validation;
use Phalcon\Validation\Validator\Uniqueness;

class Urun extends Model
{
    protected $ID;
    protected $adi;
    protected $urun_kodu;
    protected $fiyat;
    protected $kdv_orani;
    protected $indirimli_fiyat;
    protected $stok;
    protected $urun_foto;
    protected $olusturulma_tarihi;
    protected $son_guncellenme_tarihi;
    protected $aciklama;

    public function validation()
    {
        $validator = new Validation();

        $validator->add(
            'urun_kodu',
            new Uniqueness(
                [
                    'message' => 'ürün kodu benzersiz olmalıdır.',
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
    public function getUrunKodu()
    {
        return $this->urun_kodu;
    }

    /**
     * @param mixed $urun_kodu
     */
    public function setUrunKodu($urun_kodu): void
    {
        $this->urun_kodu = $urun_kodu;
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
    public function getKdvOrani()
    {
        return $this->kdv_orani;
    }

    /**
     * @param mixed $kdv_orani
     */
    public function setKdvOrani($kdv_orani): void
    {
        $this->kdv_orani = $kdv_orani;
    }

    /**
     * @return mixed
     */
    public function getİndirimliFiyat()
    {
        return $this->indirimli_fiyat;
    }

    /**
     * @param mixed $indirimli_fiyat
     */
    public function setİndirimliFiyat($indirimli_fiyat): void
    {
        $this->indirimli_fiyat = $indirimli_fiyat;
    }

    /**
     * @return mixed
     */
    public function getStok()
    {
        return $this->stok;
    }

    /**
     * @param mixed $stok
     */
    public function setStok($stok): void
    {
        $this->stok = $stok;
    }

    /**
     * @return mixed
     */
    public function getUrunFoto()
    {
        return $this->urun_foto;
    }

    /**
     * @param mixed $urun_foto
     */
    public function setUrunFoto($urun_foto): void
    {
        $this->urun_foto = $urun_foto;
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

}