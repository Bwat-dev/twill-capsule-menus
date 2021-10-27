@if($children->count() > 0)
    <div class="links-title">
        <div href="{{$url}}" target="{{$item->link_target}}" class="{{$class}}">{{$item->title}}</div>
    </div>
    <div>
        @foreach($children as $child)
            <x-menus-item :current="$current" :item="$child"/>
        @endforeach
    </div>
@else
    <a href="{{$url}}" target="{{$item->link_target}}" class="{{$class}}">{{$item->title}}</a>
@endif
