<?php

namespace quieteroks\presenter\tests\models;

use yii\base\Model;

class SimpleModel extends Model
{
    public $name;
    public $password;
    public $time;

    public function getType()
    {
        return 'model';
    }

    public function toString()
    {
        return "Name: {$this->name} ({$this->time})";
    }
}
