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
## Front

You can generate a base menu in your front page  like this :
```php
<x-menus-navigation id="3"/> //id of the menu
```
 blade components files can be publish:

```
php artisan vendor:publish --tag=twill-capsule-menus-views
```
### 

In your Model you can define an url method :

```php
    public function url($locale=null)
    {
        $locale = empty($locale) ? App::getLocale() : $locale;

        return route('pages.show', [
                'slug' => empty($this->getSlug($locale)) ? $this->getSlug() : $this->getSlug($locale),
            ], false);
    }
```

### Active link

To handle active link in the generated menu you have to register your layout in :

```php
    //config/twill.php
    'menus' => [
        'layoutViewComposer' => 'layouts.*'
    ]
```
