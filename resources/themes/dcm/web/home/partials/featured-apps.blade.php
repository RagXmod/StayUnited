@if(isset($apps) && $apps)
    @foreach($apps as $app)
        <div class="col-md-12">
            <div class="block block-themed">
                <div class="block-header bg-light">
                    <h3 class="block-title"><i class="{{ $app['icon'] ?? ''}}"></i> {{ $app['title'] ?? 'Apps' }} </h3>
                    <div class="block-options">
                        <a href="{{ $app['more_url'] ?? '#' }}" class="btn-block-option">
                                {{ __('dcm.more') }} <i class="fas fa-angle-double-right"></i>
                        </a>
                    </div>
                </div>
                <div class="block-content block-content-hover block-content-no-pad row">
                    @if( isset($app['apps']) )
                        @foreach($app['apps'] as $item)
                            @include('web.app.partials._app',['item' => $item ])
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    @endforeach
@else
    <div class="col-md-12">
        <div class="block block-themed">
            <div class="block-header bg-light">
                <h3 class="block-title">
                    {{ __('dcm.setup_apps')}}
                </h3>
            </div>
        </div>
    </div>
@endif