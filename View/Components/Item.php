<?php

namespace App\Twill\Capsules\Menus\View\Components;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\View\Component;

class Item extends Component
{
    public $current;
    public $item;
    public $children;
    public $isChildren;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($current, $item, $isChildren=false)
    {
        $this->current = $current;
        $this->item = $item;
        $this->isChildren = $isChildren;
    }

    public function url()
    {
        if($this->item->getRelated('related_menu')->count() > 0) {
            $model = $this->item->getRelated('related_menu')->first();

            if($model instanceof Model) {
                return method_exists($model, 'url') ? $model->url() : $model->slug;
            }

            return '';
        }

        if($this->item->route_path) {
            return !empty($this->item->params) ? route($this->item->route_path).$this->item->params : route($this->item->route_path);
        }

        $locale = App::getLocale();
        $url = $this->item->url;

        if(!is_null($this->item->anchor)) {
            $url = $locale.$this->item->url.$this->item->anchor;
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
        $class = '';
        $current = $this->current;
        $item = $this->item;


        if(!is_null($current) && $current->id === $item->id) {
            $class.= ' current ';
        }

        if(!is_null($current) && $item->isSelfOrAncestorOf($current)) {
            $class.= ' active ';
        }

        $this->children = $item->children;

        return view('menus::components.item', [
            'class' => $class
        ]);
    }
}
