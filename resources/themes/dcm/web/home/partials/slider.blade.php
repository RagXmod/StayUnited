
@if(isset($sliders) && $sliders)

<div class="col-md-12 mb-4">

    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
            @foreach ($sliders as $index => $slider)
            <li data-target="#carouselExampleIndicators" data-slide-to="{{ $index }}" class="@if ($loop->first) active @endif"></li>
            @endforeach
        </ol>
        <div class="carousel-inner">

            @foreach ($sliders as $slider)
                <div class="carousel-item  @if ($loop->first) active @endif">
                    <a href="#">
                    <img src="{{ $slider['image_url'] }}" class="d-block w-100" alt="{{ $slider['title'] ?? ''}}" height="350">
                    <div class="carousel-caption">
                        <h3>{{ $slider['title'] ?? ''}}</h3>
                    </div>
                    </a>
                </div>
            @endforeach

        </div>
        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
        </div>
</div>
@endif