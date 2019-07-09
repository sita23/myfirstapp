<?php


namespace Sevo\Model;

use Phalcon\Mvc\Model;
class Product extends Model
{
    protected $id;
    protected $name;
    protected $stock;
    protected $consumption_date;
    protected $production_date;
    protected $created_at;
    protected $last_modified_at;
    protected $price;
    protected $category_id;

    /**
     * @return mixed
     */
    public function getCategoryİd()
    {
        return $this->category_id;
    }

    /**
     * @param mixed $category_id
     */
    public function setCategoryİd($category_id): void
    {
        $this->category_id = $category_id;
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
    public function getStock()
    {
        return $this->stock;
    }

    /**
     * @param mixed $stock
     */
    public function setStock($stock)
    {
        $this->stock = $stock;
    }

    /**
     * @return mixed
     */
    public function getConsumptionDate()
    {
        return $this->consumption_date;
    }

    /**
     * @param mixed $consumption_date
     */
    public function setConsumptionDate($consumption_date)
    {
        $this->consumption_date = $consumption_date;
    }

    /**
     * @return mixed
     */
    public function getProductionDate()
    {
        return $this->production_date;
    }

    /**
     * @param mixed $production_date
     */
    public function setProductionDate($production_date)
    {
        $this->production_date = $production_date;
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
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

}