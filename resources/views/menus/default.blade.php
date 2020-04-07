<ul>
@foreach ($items as $item)

    @php
        $listItemClass = '';
        if(route($route, $item[$slug]) == url()->current()){
            $listItemClass .= 'active';
        }
    @endphp

    <li @if($listItemClass) class="{{ $listItemClass }}" @endif>
        <a @if($route) href="{{ route($route, $item[$slug]) }}" @else href="#" @endif>
            {{ $item['title'] }}
        </a>
        @if(isset($item['children']))
            @include('voyager-site::menus.default', ['items' => $item['children'], 'route' => 'page', 'slug' => 'slug'])
        @endif
    </li>
@endforeach
</ul>
