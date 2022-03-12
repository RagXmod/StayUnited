@include('web.sidebar.popular')

{{-- ads start --}}
<div class="block">
    <div class="block-content block-content-full">
        <div class="block ">
            @include('common.ads-placement',[ 'identifier' => 'sidebar'])
        </div>
    </div>
</div>
@include('web.sidebar.category')