<?php

namespace quieteroks\presenter\strategies;

use yii\base\UnknownMethodException;

class ArrayStrategy implements ModelStrategyInterface
{
    /**
     * @var array
     */
    protected $model = [];

    /**
     * Array accessor strategy constructor.
     *
     * @param array $model
     */
    public function __construct(array $model)
    {
        $this->model = $model;
    }

    /**
     * Checks if a property is exist in model.
     *
     * @param string $name
     * @return bool
     */
    public function hasAttribute(string $name): bool
    {
        return isset($this->model[$name]) || array_key_exists($name, $this->model);
    }

    /**
     * Returns a property value from model.
     *
     * @param string $name
     * @return mixed
     */
    public function getAttribute(string $name)
    {
        return $this->model[$name];
    }

    /**
     * Returns model properties list.
     *
     * @return array
     */
    public function fields(): array
    {
        return array_keys($this->model);
    }

    /**
     * Checks if a method exist in model.
     *
     * @param string $name
     * @return bool
     */
    public function hasMethod(string $name): bool
    {
        return false;
    }

    /**
     * Calls the named method with params in model.
     *
     * @param string $name
     * @param array $params
     * @return mixed
     */
    public function callMethod(string $name, array $params = [])
    {
        throw new UnknownMethodException();
    }
}
