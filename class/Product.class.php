<?php

class Product
{
    protected $id = 0;
    protected $name = "";
    protected $price = 0.0;

    // constructeur ------------------------------------------------

    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            $method = "set" . ucfirst(str_replace("product_", "", $key));
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }

    // getters -----------------------------------------------------
    public function getId($raw = false)
    {
        return $raw ? $this->id : htmlspecialchars($this->id);
    }

    public function getName($raw = false)
    {
        return $raw ? $this->name : htmlspecialchars($this->name);
    }

    public function getPrice($raw = false)
    {
        return $raw ? $this->price : htmlspecialchars($this->price);
    }

    // setters ------------------------------------------------------
    public function setId($value)
    {
        if (is_numeric($value) && $value >= 0) {
            $this->id = $value;
            return true;
        }
        return false;
    }

    public function setName($value)
    {
        $this->name = $value;
        return true;
    }

    public function setPrice($value)
    {
        if (is_numeric($value) && $value >= 0) {
            $this->price = $value;
            return true;
        }
        return false;
    }
}
