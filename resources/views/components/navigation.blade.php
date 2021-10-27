@if(isset($menu) && $menu->menuItems->count() > 0)
    @foreach($menu->menuItems as $item)
        @unless($item->parent)
            <div class="links-container">
                <x-menus-item :current="$current" :item="$item"/>
            </div>
        @endunless
    @endforeach
@endif
