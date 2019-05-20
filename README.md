# Yii2 presenter

The presenter is view part of model layer. It retrieves data
from the model, and formats for display in view.

The presenter is simple way to decorate model for view
of you MVC framework. The best way for presenter is to convert
model layer to view and replace view part itself.

But, you can put all view logic according to model in presenter
and also provide all data for view from the presenter. All
methods have to be encapsulated in common interface and after
it they have to replace  the presenter (maybe even with model)
to fully change view part for other models.

Typical example of such presenters are following 
for CRUD operation in admin panel:
- Presenter for the model’s list through the GridView 
- Presenter for specification of the model through the DetailView
- Presenter of the form for creation and updating models

## Installation

Install with composer cli:

```
composer require quieteroks/yii-presenter
```

Or add package in `composer.json` to `require` part:

```
"quieteroks/yii-presenter": "^1.0"
```

## Usage

The presenter can decorate `array`, `object` or `yii\base\Model`
and access all decorated type as presented object.

- Minimal presenter object:
```php
/**
 * DocBlock for description all field
 */
class UserPresenter extends \quieteroks\presenter\Presenter
{
}
```

- The presenter can hide or rename base properties:
```php
class UserPresenter extends \quieteroks\presenter\Presenter
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
}
```

- The presenter can decorate and add property via getter method:
```php
class UserPresenter extends \quieteroks\presenter\Presenter
{
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
```

## Access priority

The presenter finds and gives access to property in a priority:

- Pubic property of the presenter
- Getter method in the presenter
- Getter method in the decorated model
- Public properties in the decorated model

All getter method are wrapped in cameCase: `created_at => getCreatedAt`
it’s also can be relevant for getter method in the decorated model.
There is no access for methods like getCreated_at.
