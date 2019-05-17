<?php

namespace quieteroks\presenter\tests\models;

use quieteroks\presenter\Presenter;

/**
 * @property string $username
 * @property string $time
 */
class AdvancedTestPresenter extends Presenter
{
    protected $renamed = [
        'username' => 'name',
    ];

    protected $hidden = [
        'password'
    ];

    public function getTime()
    {
        return date('H:i:s', $this->getRealPropertyValue('time'));
    }
}
