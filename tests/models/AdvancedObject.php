<?php

namespace quieteroks\presenter\tests\models;

class AdvancedObject
{
    public $name;
    public $password;
    public $time;

    public function __construct(array $data)
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    public function getName()
    {
        return "[{$this->name}]";
    }
}
