<?php

namespace quieteroks\presenter\strategies;

use yii\base\Model;

class ModelStrategy extends ObjectStrategy
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * @inheritDoc
     */
    public function hasAttribute(string $name): bool
    {
        return $this->model->canGetProperty($name);
    }

    /**
     * @inheritDoc
     */
    public function fields(): array
    {
        return $this->model->attributes();
    }

    /**
     * @inheritDoc
     */
    public function hasMethod(string $name): bool
    {
        return $this->model->hasMethod($name);
    }
}
