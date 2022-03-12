@if(isset($navigations))
    <div class="pt-4 px-4 bg-body-dark rounded push animated fadeIn">
        <h3>{{ $navigationTitle ?? 'Shortcut Menu'}}</h3>
        <div class="row row-deck">
            @foreach($navigations as $nav)
                <div class="col-6 col-md-4 col-xl-3">
                    <a class="btn block block-rounded block-link-pop text-center d-flex align-items-center {{ $nav['is_disabled'] ?? '' }}"
                        href="{{ $nav['link'] }}">
                        <div class="block-content">
                            <p class="font-w600 font-size-sm text-uppercase">
                                @if( isset($nav['icon']))<i class="{{ $nav['fa'] ?? $nav['fa'] ?? 'fa' }} fa-{{  $nav['icon'] }}"></i> @endif {{ $nav['title'] }}
                                @if( isset($nav['sub_title']))<br/><small> {{ $nav['sub_title'] }}</small>@endif
                            </p>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
@endif