<div class="col-md-12">
    <div class="block block-themed">
        <div class="block-header bg-light">
            <h3 class="block-title"><i class="{{ $app['icon'] ?? ''}}"></i> {{ __('dcm.home_newest_app_title') }}</h3>
        </div>
        <div class="block-content block-content-hover block-content-no-pad row">
            @if(isset($newest_apps) && $newest_apps)
                @foreach($newest_apps as $item)
                    @include('web.app.partials._app',['item' => $item ])
                @endforeach
            @endif
        </div>
    </div>
</div>
