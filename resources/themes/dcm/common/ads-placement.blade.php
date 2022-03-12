@php
    $adsCollect =  showAds( $identifier ?? [] );
@endphp
@if( $adsCollect )
    <div class="col-md-12 mt-2 mb-2">
        @foreach ($adsCollect as $item)
            {!! $item['ads_code'] !!}
        @endforeach
    </div>
@endif