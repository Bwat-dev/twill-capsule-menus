<?php

namespace App\Twill\Capsules\Menus\Http\Controllers;

use A17\Twill\Http\Controllers\Admin\ModuleController;
use A17\Twill\Models\Behaviors\HasSlug;
use App\Twill\Capsules\Menus\Http\Requests\MenuItemRequest;
use App\Twill\Capsules\Menus\Models\Menu;
use App\Twill\Capsules\Menus\Models\MenuItem;
use App\Twill\Capsules\Menus\Repositories\MenuItemRepository;
use App\Twill\Capsules\Menus\Repositories\MenuRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class MenuMenuItemController extends ModuleController
{
    protected $moduleName = 'menus.menuItems';
    protected $modelName = 'menus';

    protected $indexOptions = [
        'reorder' => true,
        'skipCreateModal' => true,
    ];

    protected function indexData($request)
    {
        $menu = Menu::find($this->submoduleParentId);
        return [
            'breadcrumb' => [
                [
                    'label' => 'Menus',
                    'url' => moduleRoute('menus', '', 'index'),
                ],
                [
                    'label' => $menu->title,
                ],

            ],
            'nested' => true,
            'nestedDepth' => 2, // this controls the allowed depth in UI
        ];
    }

    protected function transformIndexItems($items)
    {
        return $items->toTree();
    }

    protected function indexItemData($item)
    {
        return ($item->children ? [
            'children' => $this->getIndexTableData($item->children),
        ] : []);
    }



    public function formData($request)
    {
        $menu = Menu::find($this->submoduleParentId);
        $item = MenuItem::find($request->route('menuItem'));


        return [
            'menu' => $menu,
            'breadcrumb' => [
                [
                    'label' => 'Menus',
                    'url' => moduleRoute('menus', '', 'index',),
                ],

                [
                    'label' => $menu->title,
                    'url' => moduleRoute('menuItems', 'menus', 'index', [
                        'menu' =>$menu
                    ]),
                ],
                [
                    'label' => $item->title ?? 'item' ,
                ],

            ],
        ];
    }

    public function getRepositoryClass($model)
    {
        return MenuItemRepository::class;
    }

    public function getFormRequestClass()
    {
        return MenuItemRequest::class;
    }

    protected function getModulePermalinkBase()
    {
        $base = '';
        $moduleParts = explode('.', $this->moduleName);

        foreach ($moduleParts as $index => $name) {
            if (array_key_last($moduleParts) !== $index) {
                $singularName = Str::singular($name);
                $modelClass = config('twill.namespace') . '\\Models\\' . Str::studly($singularName);

                if(!@class_exists($modelClass)) {
                    $modelClass = $this->getCapsuleByModule($name)['model'];
                }

                $model = (new $modelClass)->findOrFail(request()->route()->parameter($singularName));
                $hasSlug = Arr::has(class_uses($modelClass), HasSlug::class);

                $base .= $name . '/' . ($hasSlug ? $model->slug : $model->id) . '/';
            } else {
                $base .= $name;
            }
        }

        return $base;
    }

    protected function getParentModuleForeignKey()
    {
        return 'menu_id';
    }


    protected function getViewPrefix() {
        return 'Menus.resources.views.admin.MenuItems';
    }

}
