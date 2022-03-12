<li class="nav-main-item">
    @if ( isset($item['children']) && count($item['children']) > 0)

        <a class="nav-main-link nav-main-link-submenu nav-main-submenu-right" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
            <span class="nav-main-link-name text-uppercase">
                    <i class="nav-main-link-icon {{  $item['icon'] ?? 'fab fa-android' }}"></i> {{ $item['name'] ?? $item['text'] ?? '' }}
            </span>
        </a>
        <ul class="nav-main-submenu">
            @each('common.partials.navigation-item', $item['children'] , 'item')
        </ul>
    @else
        <a class="nav-main-link active" title="{{  $item['title'] ?? '' }}" href="{{ $item['href'] ?? route('web.home.index') }}">
            <span class="nav-main-link-name text-uppercase">
                <i class="nav-main-link-icon {{  $item['icon'] ?? 'fab fa-android' }}"></i> {{ $item['name'] ?? $item['text'] ?? '' }}
            </span>
        </a>
    @endif
</li>