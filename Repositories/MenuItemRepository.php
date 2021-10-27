<?php

namespace App\Twill\Capsules\Menus\Repositories;

use A17\Twill\Repositories\Behaviors\HandleMedias;
use A17\Twill\Repositories\Behaviors\HandleFiles;
use A17\Twill\Repositories\ModuleRepository;
use App\Twill\Capsules\Menus\Models\MenuItem;
use Illuminate\Support\Facades\DB;

class MenuItemRepository extends ModuleRepository
{
    use HandleMedias, HandleFiles;


    protected $relatedBrowsers = ['related_menu'];

    public function __construct(MenuItem $model)
    {
        $this->model = $model;
    }

    public function getFormFields($object)
    {
        $fields = parent::getFormFields($object);
        $fields['browsers']['related_menu'] = $this->getFormFieldsForRelatedBrowser($object, 'related_menu');

        return $fields;
    }

    public function beforeSave($object, $fields)
    {
        $object->menu_id = request()->route('menu');
        parent::beforeSave($object, $fields);
    }

    public function setNewOrder($ids)
    {
        DB::transaction(function () use ($ids) {
            MenuItem::saveTreeFromIds($ids);
        }, 3);
    }
}
