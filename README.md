Capsule for twill to manage Menu
## Requirement
The capsule required nestedset package :
````
composer require kalnoy/nestedset
````

## MenuItem

A MenuItem can have Three different type of link.

### Link with module

You can link your Menuitem with browser field as related items.

in your twill.php config file:


```php
    //config/twill.php
    'menus' => [
        'linkable' => [
            [
                'name' => 'pages',
            ],

            [
                'name' => 'articles',
            ],
        ],
    ]
```
https://twill.io/docs/#using-browser-fields-as-related-items

### link with route 

You can link menuItem with a custom route like page listing or any custom route .

in your twill.php config file:

```php
    //config/twill.php
    'menus' => [
        'routes' => [
            [
                'value' => '',
                'label' =>  'Select Item',
            ],
            [
                'value' => 'pages.index',
                'label' => 'Page listing'
            ]
        ],
    ]
```
