

@if( isset($app->screenshots ) && $app->screenshots )
<div id="screenshots" class="owl-carousel owl-theme" >

        @foreach($app->screenshots  as $item)
            <div class="item">
                    <a data-fancybox="gallery" title="{{ $item->title }}" href="{{ $item->image_link }}">
                        <img  src="{{ $item->image_link }}" title="{{ $item->title }}">
                    </a>
            </div>
        @endforeach
    </div>
@endif