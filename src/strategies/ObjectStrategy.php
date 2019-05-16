<?php

namespace quieteroks\presenter\strategies;

class ObjectStrategy implements ModelStrategyInterface
{
    /**
     * @var object
     */
    protected $model;

    /**
     * Object accessor strategy constructor.
     *
     * @param object $model
     */
    public function __construct($model)
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
        return property_exists($this->model, $name);
    }

    /**
     * Returns a property value from model.
     *
     * @param string $name
     * @return mixed
     */
    public function getAttribute(string $name)
    {
        return $this->model->{$name};
    }

    /**
     * Returns model properties list.
     *
     * @return array
     */
    public function fields(): array
    {
        return array_keys(get_object_vars($this->model));
    }

    /**
     * Checks if a method exist in model.
     *
     * @param string $name
     * @return bool
     */
    public function hasMethod(string $name): bool
    {
        return method_exists($this->model, $name);
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
        if (empty($params)) {
            return call_user_func([$this->model, $name]);
        }
        return call_user_func_array([$this->model, $name], $params);
    }
}
