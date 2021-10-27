<?php

namespace App\Twill\Capsules\Menus\View\Components;

use App\Twill\Capsules\Menus\Models\Menu;
use App\Twill\Capsules\Menus\Models\MenuItem;
use Illuminate\View\Component;

class Navigation extends Component
{
    public $id;

    public $menu;

    public $current = "";

    public $showLinks;

    public function __construct($id, $showLinks=true)
    {
        $this->id = $id;
        $this->menu = Menu::with(['menuItems', 'menuItems.relatedItems'])->where('id', $id)->first();

        $this->current = $this->getCurrentMenuItem();

        $this->showLinks = $showLinks;

    }

    public function isActive()
    {
        $ids = $this->menu->menuItems->pluck('id')->all();
        if (!is_null($this->current) && in_array($this->current->id, $ids)) {
            return true;
        }

        return false;
    }


    /**
     * @return mixed
     */
    public function getCurrentMenuItem()
    {
        return config('app.currentMenuItem');
    }

    /**
     * @param MenuItem $menuItem
     * @return mixed|string
     */
    public function generateUrl(MenuItem $menuItem)
    {
        if ($menuItem->getRelated('related_menu')->count() > 0) {
            $model = $menuItem->getRelated('related_menu')->first();
            return $model->url();
        }
        $url = $menuItem->url;

        if (!is_null($menuItem->anchor)) {
            $url = $menuItem->url . $menuItem->anchor;
        }

        return $url;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('menus::components.navigation', ['menu' => $this->menu, 'showLinks' => $this->showLinks]);
    }
}
