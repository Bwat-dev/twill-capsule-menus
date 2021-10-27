<?php
namespace App\Twill\Capsules\Menus\View\Composers;

use A17\Twill\Models\RelatedItem;
use App\Twill\Capsules\Menus\Services\Breadcrumb;
use App\Twill\Capsules\Menus\Models\MenuItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\View\View;
use Illuminate\Support\Facades\Config;

class MenuComposer
{
    public function compose(View $view)
    {
        //single
        if($view->item instanceof Model) {
            $related = RelatedItem::where('related_id', $view->item->id)
                ->where('related_type', get_class($view->item))
                ->where('subject_type', 'MenuItems')
                ->first()
            ;
            if($related) {
                Config::set('app.currentMenuItem', $related->subject);

                if(!$view->breadcrumb) {
                    $view['breadcrumb'] = Breadcrumb::make($related->subject);
                }
            }
            return $view;
        }

        //List

        $menuItem = MenuItem::published()->where('route_path', request()->route()->getName());

        if(count(request()->route()->parameters) > 0) {
            $prefix = request()->route()->getAction('prefix');
            if($menuItem->count() > 1) {
                $params = last(explode($prefix, url()->current()));
                $menuItem->where('params', $params);
            }
        }
        $menuItem =  $menuItem->first();

        if($menuItem) {
            $baseBreadcrumb = Breadcrumb::make($menuItem);
            if(!$view->breadcrumb) {
                $view['breadcrumb'] =  $baseBreadcrumb;
            }
        }
        Config::set('app.currentMenuItem', $menuItem);
        return $view;
    }
}
