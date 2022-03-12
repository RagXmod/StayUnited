@if(isset($app->moreDetails) && !$app->moreDetails->isEmpty())
<div class="block block-square ">
    <div class="block-header block-header-default">
       <h3 class="block-title"> <i class="fa fa-fw fa-tags mr-1"></i> {{ __('dcm.additional_info_title', ['attr' => $app->title]) }} </h3>
    </div>
    <div class="block-content  px-3 py-3 mb-2" style="min-height:200px;">
        <ul class="list-unstyled" style="width:100%;display:inline-block;">
            @foreach($app->moreDetails as $detail)
                <li style="width:33.33%;float: left;">
                    <strong>{{ $detail->title }}:</strong>
                    <p>{{ ($detail->value ?? '--') ? $detail->value : '--' }}</p>
                </li>
            @endforeach
        </ul>


        <ul class="list-unstyled" style="width:100%;display:inline-block;">
            <li style="width:33.33%;float: left;">
                <strong>Get it on:</strong>
                <p>
                    <a title="{{ $app->title }}" rel="nofollow" href="{{ $app->app_link }}" target="_blank">
                        <img alt="{{ $app->title }}" src="{{ asset('img/gp_logo.png') }}" height="16">
                    </a>
                </p>
            </li>
            <li style="width:33.33%;float: left;">
                <strong>Report:</strong>
                <p>
                    <a title="{{ $app->title }} - {{ __('dcm.flag_as_inappropriate') }} " href="{{ route('web.home.reportcontent', ['report-url' => $app->slug]) }}">
                        {{ __('dcm.flag_as_inappropriate') }}
                    </a>
                </p>
            </li>
        </ul>
    </div>

</div>
@endif