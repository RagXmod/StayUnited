
@if(isset($app->versions) && !$app->versions->isEmpty() )
    <h2 class="content-heading">
        {{ __('dcm.app_version_title', ['attr' => $app->title]) }}
        <span class="block block-themed">
        <a href="{{ route('web.app.detail.versions', $app->slug) }}" class="btn float-right ">
                {{ __('dcm.more') }} <i class="fas fa-angle-double-right"></i>
            </a>
        </span>
    </h2>
    <div class="row">
            @php
                $myVersions =  ($limit_to ?? 0) > 0 ? $app->versions->take($limit_to) : $app->versions;
            @endphp
            @foreach($myVersions as $version)
                <div class="col-12 col-md-5 col-lg-4">
                    <div class="card border-success mb-3" >
                        <div class="card-header">
                        <i className="fa fa-info-circle"></i>  <strong>{{ __('dcm.latest_version_title',['attr' => $version->identifier]) }}</strong>
                        <span className="float-right">
                            <i className="fa fa-download"></i> <strong>{{ __('dcm.apk_title') }}</strong>
                        </span>
                        </div>
                        <div class="card-body text-dark">
                            <h6 class="card-title"> {{ str_limit($app->title, 25) }} </h6>
                            <p class="mb-2">
                                <i class="fa fa-fw fa-calendar-alt text-primary"></i>  {{ $version->created_at }}
                            </p>
                            <p class="mb-2">
                                <i class="fab fa-fw fa-android text-primary"></i>  {{ $version->size_formatted }}
                            </p>
                            <a href="{{  route('web.app.detail.download', [$app->slug, 'version' => hasher($version->id)] ) }}"
                                class="btn btn-sm btn-block btn-secondary text-uppercase">
                                <i class="fa fa-download"></i>  {{ __('dcm.download') }}
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
    </div>
@endif