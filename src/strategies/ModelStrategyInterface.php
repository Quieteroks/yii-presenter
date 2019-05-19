<?php

namespace quieteroks\presenter\strategies;

interface ModelStrategyInterface
{
    /**
     * Checks if a property is exist in model.
     *
     * @param string $name
     * @return bool
     */
    public function hasAttribute(string $name): bool;

    /**
     * Returns a property value from model.
     *
     * @param string $name
     * @return mixed
     */
    public function getAttribute(string $name);

    /**
     * Returns model properties list.
     *
     * @return array
     */
    public function fields(): array;

    /**
     * Checks if a method exist in model.
     *
     * @param string $name
     * @return bool
     */
    public function hasMethod(string $name): bool;

    /**
     * Calls the named method with params in model.
     *
     * @param string $name
     * @param array $params
     * @return mixed
     */
    public function callMethod(string $name, array $params = []);
}
