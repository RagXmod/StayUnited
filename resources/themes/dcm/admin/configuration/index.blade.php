@extends( "layouts.master-admin")

@section('content')
<div class="col-lg-12 col-xl-12 animated fadeIn">

    <div class="block block-bordered">
        <div class="block-content">
            <div class="row">
                @if( isset($navigations) )
                    @foreach($navigations as $item)
                        <div class="col-6 col-md-4 col-lg-2">
                            <a class="block block-link-shadow text-center {{ ($action_type == $item['identifier']) ? 'is-active' : '' }}" href="{{ $item['link'] }}">
                                <div class="block-content block-content-full aspect-ratio-4-3 d-flex justify-content-center align-items-center">
                                    <div>
                                        <i class="fa fa-2x fa-{{ $item['icon'] }} text-xsmooth"></i>
                                        <div class="font-w600 mt-3 text-uppercase">{{ $item['title'] }}</div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                @endif
            </div>

        </div>
    </div>

    <div class="block block-bordered">
        <div class="block-content">
            <div class="row">
                <div class="col-lg-3">
                    <h2 class="content-heading">Site Configurations
                        <div>
                            <small class="text-muted">
                                {{ $selected_action['title'] ?? ucfirst($action_type) }} settings page.
                            </small>
                        </div>
                    </h2>
                </div>
                <div class="col-lg-9 col-xl-7">
                    @include("admin.configuration.partials.{$action_type}")
                </div>
            </div>
        </div>
    </div>
</div>


@endsection


@push('javascript')

<script>
    $(document).ready(function(){

        window.dcmUri = (window.dcmUri || {});
        window.dcmUri = {
            resource: '{!! route("dcm-configuration-resource.index") !!}',
        }
    });
</script>

@endpush
