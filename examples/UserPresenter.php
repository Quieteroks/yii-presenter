<?php

namespace quieteroks\presenter\examples;

use quieteroks\presenter\Presenter;

/**
 * UserPresenter
 *
 * @property integer $id
 * @property string $fullName
 * @property string $first_name
 * @property string $middle_name
 * @property string $last_name
 * @property string $createdAt
 * @property string $updatedAt
 *
 * @see \common\models\User
 */
class UserPresenter extends Presenter
{
    /**
     * @var array Protect password hash from access via presenter
     */
    protected $hidden = [
        'password_hash',
    ];
    /**
     * @var array Decorate to camelCase properties
     */
    protected $renamed = [
        'createdAt' => 'created_at',
        'updatedAt' => 'updated_at',
    ];

    /**
     * Returns fullname property
     *
     * @return string
     */
    protected function getFullName()
    {
        return implode(' ', array_filter([
            $this->getRealPropertyValue('last_name'),
            $this->getRealPropertyValue('first_name'),
            $this->getRealPropertyValue('middle_name'),
        ]));
    }
}
