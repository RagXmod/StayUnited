@extends( "layouts.master-admin")

@section('content')
<div class="col-lg-12 col-xl-12 animated fadeIn">

    <div class="block">
        <div class="block-content">

            <ol class="breadcrumb breadcrumb-alt push">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.ads.index') }}"><i class="fas fa-hand-holding-usd mr-2"></i>  Advertisement Informations</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">{{ $item->title ?? 'Create New Ads' }}</li>
            </ol>
        <div id="admin-ads"  data-props='@json($item)' data-pageindex="{{ route("admin.ads.index") }}"></div>
        </div>
    </div>
</div>
@endsection


@push('stylesheet')
    <link rel="stylesheet" href="{{ asset('vendor/summernote/summernote-bs4.css') }}">
    <style>

    </style>
@endpush
@push('javascript')

    <script>
        $(document).ready(function(){

            window.dcmUri = (window.dcmUri || {});
            window.dcmUri = {
                resource  : '{!! route("dcm-ads-resource.index") !!}',
            }
        });
    </script>
    <script src="{{ asset('vendor/summernote/summernote-bs4.min.js') }}"></script>
    <script src="{{ mix('js/admin-ads.js') }}"></script>

@endpush
