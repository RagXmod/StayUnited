<div class="col-md-4 col-sm-6 col-xs-6">
    <a class="block block-rounded block-link-shadow" href="{{ $item->app_detail_url }}">
        <div class="block-content block-content-full d-flex align-items-center justify-content-between">
            <div class="item  block-app-image">
                <img
                    src="{{ $item->app_image_url ?? asset('img/default-app.png') }}"
                width="100%" height="100%">
            </div>
            <div class="ml-3 text-right">
                <p class="font-w600 mb-0 app-title">
                    {{ str_limit($item->title,18) }}
                </p>
                <div class="stars mb-0">
                    <span class="score" title="{{ __('dcm.app_average_rating',['attr' => $item->title, 'rating' => $item->current_ratings ]) }}" style="width: {{ ceil(((double) $item->current_ratings  / 5) * 100) }}%;"></span>
                    <span class="star">{{ $item->current_ratings }}</span>

                </div>
                <button type="button" class="block-app-download btn btn-outline-success btn-sm">
                    <i class="fa fa-fw fa-download mr-1"></i> {{ __('dcm.download') }}
                </button>

            </div>
        </div>
    </a>
</div>