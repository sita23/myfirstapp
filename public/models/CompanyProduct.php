<?php

namespace Sevo\Model;

use Phalcon\Validation;
use Phalcon\Validation\Validator\Uniqueness;
use Phalcon\Validation\Validator\InclusionIn;
use Phalcon\Security\Random;
class CompanyProduct extends Model
{
    protected $id;
    protected $company_id;
    protected $product_id;
    protected $total;
    protected $uuid;

    public function beforeValidationOnCreate()
    {
        $this->generateUuid();
    }


    /** @var Company $_Company */

    public function initialize()
    {
        $this->belongsTo(
            'company_id',
            'Sevo\Model\Company',
            'id',
            [
                'alias' => '_Company',
            ]
        );

    /** @var Product $_Product */
        $this->belongsTo(
            'product_id',
            'Sevo\Model\Product',
            'id',
            [
                'alias' => '_Product',
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
     * @return Company
     */
    public function getCompany()
    {
        return $this->_Company;
    }

    /**
     * @return Product
     */
    public function getProduct()
    {
        return $this->_Product;
    }

    /**
     * @return mixed
     */
    public function getCompanyId()
    {
        return $this->company_id;
    }

    /**
     * @param mixed $company_id
     */
    public function setCompanyId($company_id): void
    {
        $this->company_id = $company_id;
    }

    /**
     * @return mixed
     */
    public function getProductİd()
    {
        return $this->product_id;
    }

    /**
     * @param mixed $product_id
     */
    public function setProductİd($product_id): void
    {
        $this->product_id = $product_id;
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
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @param mixed $total
     */
    public function setTotal($total): void
    {
        $this->total = $total;
    }
}
