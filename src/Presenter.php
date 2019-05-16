<?php

namespace quieteroks\presenter;

use yii\base\Arrayable;
use yii\base\ArrayableTrait;
use yii\base\Model;
use yii\base\NotSupportedException;
use yii\base\UnknownMethodException;
use yii\base\UnknownPropertyException;
use yii\helpers\Inflector;
use quieteroks\presenter\strategies\ArrayStrategy;
use quieteroks\presenter\strategies\ModelStrategy;
use quieteroks\presenter\strategies\ModelStrategyInterface;
use quieteroks\presenter\strategies\ObjectStrategy;

class Presenter implements Arrayable
{
    use ArrayableTrait;

    /**
     * Renamed properties for decorate real name in model.
     *
     * The syntax is like the following:
     * ```php
     * [
     *     'newName' => 'realName'
     * ]
     * ```
     *
     * @var array
     */
    protected $renamed = [];
    /**
     * Hidden properties list with real name in model.
     *
     * @var array
     */
    protected $hidden = [];
    /**
     * @var ModelStrategyInterface
     */
    protected $model;

    /**
     * Presenter constructor.
     *
     * @param array|object $model
     * @throws NotSupportedException
     */
    public function __construct($model)
    {
        $this->model = static::wrapStrategy($model);
    }

    /**
     * Returns the list of fields that should be returned by default by [[toArray()]]
     * when no specific fields are specified.
     *
     * @return array|false
     */
    public function fields(): array
    {
        $fields = [];
        $renamed = array_flip($this->renamed);
        foreach ($this->model->fields() as $field) {
            if (isset($renamed[$field])) {
                $field = $renamed[$field];
            }
            if ($this->notHidden($field)) {
                $fields[] = $field;
            }
        }
        return array_combine($fields, $fields);
    }

    /**
     * Returns the value from presenter by getter method,
     * or from model by getter or defined property.
     *
     * @param string $name
     * @return mixed
     * @throws UnknownPropertyException
     */
    public function __get($name)
    {
        if ($this->hasGetterProperty($name)) {
            return $this->getGetterProperty($name);
        }
        if ($this->hasRealProperty($name)) {
            return $this->getRealPropertyValue($name);
        }
        throw new UnknownPropertyException(
            'Getting unknown property: ' . get_class($this) . '::' . $name
        );
    }

    /**
     * Checks if a property exist like as getter in presenter
     * or in decorated model like as getter or properties.
     *
     * @param string $name
     * @return bool
     */
    public function __isset($name)
    {
        return $this->hasGetterProperty($name)
            || $this->hasRealProperty($name);
    }

    /**
     * Calls the named method from decorated model.
     *
     * @param string $name
     * @param array $params
     * @return mixed
     * @throws UnknownMethodException
     */
    public function __call($name, $params)
    {
        if ($this->model->hasMethod($name)) {
            return $this->model->callMethod($name, $params);
        }
        throw new UnknownMethodException(
            'Calling unknown method: ' . get_class($this) . "::$name()"
        );
    }

    /**
     * Checks if a property exist like getter method in presenter.
     *
     * @param string $name
     * @return bool
     */
    protected function hasGetterProperty(string $name): bool
    {
        return method_exists($this, $this->getGetterMethod($name));
    }

    /**
     * Returns presented property value.
     *
     * @param string $name
     * @return mixed
     */
    protected function getGetterProperty(string $name)
    {
        return call_user_func([$this, $this->getGetterMethod($name)]);
    }

    /**
     * Checks if a property exist in presented model
     * by getter method and model attribute.
     *
     * @param string $name
     * @return bool
     */
    protected function hasRealProperty(string $name): bool
    {
        $realName = $this->getRealName($name);
        if ($this->notHidden($realName)) {
            return $this->model->hasMethod($this->getGetterMethod($realName))
                || $this->model->hasAttribute($realName);
        }
        return false;
    }

    /**
     * Returns a real property value from presented model
     * by getter method and model attribute.
     *
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    protected function getRealPropertyValue(string $name, $default = null)
    {
        $realName = $this->getRealName($name);
        $realGetter = $this->getGetterMethod($realName);
        if ($this->model->hasMethod($realGetter)) {
            return $this->model->callMethod($realGetter);
        }
        if ($this->model->hasAttribute($realName)) {
            return $this->model->getAttribute($realName);
        }
        return $default;
    }

    /**
     * Returns getter method name from property name.
     *
     * @param string $name
     * @return string
     */
    protected function getGetterMethod(string $name): string
    {
        return 'get' . Inflector::camelize($name);
    }

    /**
     * Returns real property name.
     * Checks name by renamed properties.
     *
     * @param string $name
     * @return string
     */
    protected function getRealName(string $name): string
    {
        return $this->renamed[$name] ?? $name;
    }

    /**
     * Checks if a property is hidden from public access.
     *
     * @param string $name
     * @return bool
     */
    protected function notHidden(string $name): bool
    {
        return !in_array($name, $this->hidden);
    }

    /**
     * Wrap model to strategy object for use polymorphic
     * access interface to different type of model.
     *
     * @param array|object $model
     * @return ModelStrategyInterface
     * @throws NotSupportedException
     */
    public static function wrapStrategy($model): ModelStrategyInterface
    {
        if ($model instanceof ModelStrategyInterface) {
            return $model;
        }
        if ($model instanceof Model) {
            return new ModelStrategy($model);
        }
        if (is_object($model)) {
            return new ObjectStrategy($model);
        }
        if (is_array($model)) {
            return new ArrayStrategy($model);
        }
        throw new NotSupportedException(
            'Model type not support for presenter.'
        );
    }
}
