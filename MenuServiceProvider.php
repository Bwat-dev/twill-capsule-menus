<?php


namespace App\Twill\Capsules\Menus;


use App\Twill\Capsules\Menus\Models\Menu;
use App\Twill\Capsules\Menus\Models\MenuItem;
use App\Twill\Capsules\Menus\View\Components\Item;
use App\Twill\Capsules\Menus\View\Components\Navigation;
use App\Twill\Capsules\Menus\View\Composers\MenuComposer;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
{

    public function boot()
    {
        Relation::morphMap([
            'menus' => Menu::class,
            'menu_items' => MenuItem::class
        ]);

        $this->loadViewComponentsAs('menus', [
            Navigation::class,
            Item::class,
        ]);

        $this->loadViewsFrom(__DIR__.'/resources/views', 'menus');

        $this->publishes([
            __DIR__.'/resources/views' => resource_path('views/vendor/menus'),
        ], 'twill-capsule-menus-views');


        View::composer(config('twill.menus.layoutViewComposer'), MenuComposer::class);

    }
}
