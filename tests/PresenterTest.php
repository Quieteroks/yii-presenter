<?php

namespace quieteroks\presenter\tests;

use PHPUnit\Framework\TestCase;
use quieteroks\presenter\tests\models\AdvancedObject;
use quieteroks\presenter\tests\models\AdvancedTestPresenter;
use quieteroks\presenter\tests\models\SimpleModel;
use quieteroks\presenter\tests\models\SimpleObject;
use quieteroks\presenter\tests\models\SimpleTestPresenter;
use yii\base\NotSupportedException;
use yii\base\UnknownMethodException;
use yii\base\UnknownPropertyException;

class PresenterTest extends TestCase
{
    public function testArrayPresenter()
    {
        $model = $this->getSimpleArrayModel();
        $presenter = new SimpleTestPresenter($model);

        $this->assertEquals($model['name'], $presenter->name);
        $this->assertEquals($model['password'], $presenter->password);
        $this->assertEquals($model['time'], $presenter->time);
        $this->assertEquals($model, $presenter->toArray());
    }

    public function testObjectPresenter()
    {
        $model = new SimpleObject($this->getSimpleArrayModel());
        $presenter = new SimpleTestPresenter($model);

        $this->assertEquals($model->name, $presenter->name);
        $this->assertEquals($model->password, $presenter->password);
        $this->assertEquals($model->time, $presenter->time);
        $this->assertEquals($model->getType(), $presenter->type);
        $this->assertEquals($model->toString(), $presenter->toString());
    }

    public function testModelPresenter()
    {
        $model = new SimpleModel($this->getSimpleArrayModel());
        $presenter = new SimpleTestPresenter($model);

        $this->assertEquals($model->name, $presenter->name);
        $this->assertEquals($model->password, $presenter->password);
        $this->assertEquals($model->time, $presenter->time);
        $this->assertEquals($model->getType(), $presenter->type);
        $this->assertEquals($model->toString(), $presenter->toString());
    }

    public function testRenamedProperties()
    {
        $model = new SimpleObject($this->getSimpleArrayModel());
        $presenter = new AdvancedTestPresenter($model);

        $this->assertEquals($model->name, $presenter->username);

        $array = $presenter->toArray();
        $this->assertArrayHasKey('username', $array);
        $this->assertArrayNotHasKey('name', $array);
    }

    public function testHiddenProperties()
    {
        $model = new SimpleModel($this->getSimpleArrayModel());
        $presenter = new AdvancedTestPresenter($model);

        $this->assertNotTrue(isset($presenter->password));
        $this->assertArrayNotHasKey('password', $presenter->toArray());
    }

    public function testFormattedProperties()
    {
        $model = new AdvancedObject($this->getSimpleArrayModel());
        $presenter = new AdvancedTestPresenter($model);

        $this->assertEquals($model->getName(), $presenter->username);
        $this->assertEquals($presenter->time, $presenter->getTime());
    }

    public function testNotSupportTypeModel()
    {
        $this->expectException(NotSupportedException::class);
        $presenter = new SimpleTestPresenter(1);
    }

    public function testCallUnknownMethod()
    {
        $model = $this->getSimpleArrayModel();
        $presenter = new SimpleTestPresenter($model);

        $this->expectException(UnknownMethodException::class);
        $presenter->toString();
    }

    public function testGetUnknownProperty()
    {
        $model = $this->getSimpleArrayModel();
        $presenter = new SimpleTestPresenter($model);

        $this->expectException(UnknownPropertyException::class);
        $presenter->username;
    }

    protected function getSimpleArrayModel()
    {
        return [
            'name' => 'Name',
            'password' => md5('password'),
            'time' => time(),
        ];
    }
}
