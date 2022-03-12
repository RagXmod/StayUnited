@if(isset($navigations))
<div class="pt-4 px-4 bg-body-dark rounded push animated fadeIn">
    <h2>Shortcut Application Tools</h2>
    <div class="row row-deck">


        @foreach($navigations as $item)
            <div class="col-6 col-md-4 col-xl-2">
                <a class="block block-rounded block-link-pop text-center d-flex align-items-center"
                    href="{{ $item['link'] }}">
                    <div class="block-content">
                        <p class="font-w600 font-size-sm text-uppercase">
                            {{ $item['title'] }}
                        </p>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</div>
@endif