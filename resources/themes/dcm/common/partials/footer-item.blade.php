<li>
    <a title="{{ $item['title'] ?? $item['text'] ?? '' }}" class="text-muted" href="{{ $item['href']  ?? '#' }}">
        <i class="{{ $item['icon'] ?? ''}} mr-1"></i> {{ $item['title'] ?? $item['text'] ?? ''}}
    </a>
</li>
@if( isset($item['children']) && count($item['children']) > 0 )

    <ul class="text-small pl-4">
            @each('common.partials.footer-item', $item['children'], 'item')
    </ul>
@endif