<div class="block block-rounded">
    <div class="block-content block-content-full bg-gd-fruit text-center">
        <a class="item item-circle mx-auto bg-black-25" href="javascript:void(0)">
            <i class="fab fa-2x fa-android text-white"></i>
        </a>
        <p class="text-white font-size-h3 font-w300 mt-3 mb-0">
           {{ __('dcm.most_viewed_apps_title') }}
        </p>
    </div>
    <div class="block-content block-content-full">

        <table class="table table-borderless table-striped table-hover">
            <tbody>

                @if(isset( $sidebar['most_viewed_apps']))

                    @foreach ( $sidebar['most_viewed_apps'] as $index => $app)

                        @php
                            switch ($index) {
                                case 0:
                                    $ribbonColor = 'danger';
                                    break;
                                case 1:
                                    $ribbonColor = 'warning';
                                    break;
                                case 2:
                                    $ribbonColor = 'info';
                                    break;
                                default:
                                    $ribbonColor = 'light';
                                    break;
                            }
                        @endphp
                        <tr>
                            <td style="width: 40px;">
                                <span class="ribbon ribbon-left ribbon-bookmark ribbon-{{ $ribbonColor }}">
                                    <span class="ribbon-box">
                                        {{ $app->views_count}}
                                    </span>
                                </span>
                            </td>
                            <td >
                                <img title="{{ $app->title }}" alt="{{ $app->title }}" src="{{ $app->app_image_url }}" width="30px">
                            </td>
                            <td>
                             <a title="{{ $app->title }}" href="{{  $app->app_detail_url }}" style="color: #4b4e52;">
                                <strong> {{ str_limit($app->title, 32) }}</strong>
                                <div class="stars mb-0" style="margin-left: 0;">
                                    <span class="score" title="{{ __('dcm.app_average_rating',['attr' => $app->title, 'rating' => $app->current_ratings ]) }}" style="width: {{ ceil(((double) $app->current_ratings  / 5) * 100) }}%;"></span>
                                </div>
                                    </a>
                            </td>
                            <td class="text-center">
                                <a  title="{{ $app->title }}" href="{{  $app->app_detail_url }}" class="btn btn-outline-success btn-sm">
                                    <i class="fa fa-fw fa-download mr-1"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach

                @else
                    <tr class="text-center">
                        <td> {{ __('dcm.no_apps_configured') }}</td>
                    </tr>
                @endif


            </tbody>
        </table>
        {{-- <div class="text-center">
            <a class="btn btn-hero-sm btn-hero-secondary" href="javascript:void(0)">
                <i class="fa fa-eye mr-1"></i> Show All..
            </a>
        </div> --}}
    </div>
</div>