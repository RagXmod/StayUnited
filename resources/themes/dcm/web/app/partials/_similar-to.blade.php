
@if( isset($item) && !$item->isEmpty())
<div class="col-md-12">
    <div class="block block-themed">
        <div class="block-header bg-light">
            <h3 class="block-title"><i class="fa fa-clone"></i> {{ __('dcm.similar_to', ['attr' => $app->title]) }} </h3>
            <div class="block-options">
                <a href="{{ route('web.app.detail.similar.apps', $app->slug) }}" >
                        {{ __('dcm.more')}} <i class="fas fa-angle-double-right"></i>
                </a>
            </div>
        </div>
        <div class="block-content block-content-hover block-content-no-pad row" mb-4">
            @foreach($item as $_item)
                @include('web.app.partials._app',['item' => $_item ])
            @endforeach


        </div>
    </div>
</div>
@endif