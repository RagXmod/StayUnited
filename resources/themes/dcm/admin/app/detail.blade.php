@extends( "layouts.master-admin")

@section('content')

<div class="col-lg-12 col-xl-12 animated fadeIn">

    @include('admin.app.partials.navigations')
    <div class="block">
        <div class="block-content">

            <ol class="breadcrumb breadcrumb-alt push">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.app.index') }}"><i class="fab fa-android mr-2"></i>  Apps</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">{{ $item['title'] ?? 'Create new app' }}</li>
            </ol>
            <div id="admin-app-detail"
                data-status='@json($statusCollections)'
                data-categories='@json($categoryCollections)'
                data-developers='@json($developerCollections)'
                data-props='@json($item)'
                data-uploadLimit="{{ $upload_size_limit || '~' }}"
                data-pageindex="{{ route("admin.app.index") }}">
            </div>
        </div>
    </div>
</div>
@endsection


@push('stylesheet')
    <link rel="stylesheet" href="{{ asset('vendor/summernote/summernote-bs4.css') }}">
    <style>
        .dropzone {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 5px;
            border-width: 2px;
            border-radius: 2px;
            border-color: #eeeeee;
            border-style: dashed;
            background-color: #fafafa;
            color: #bdbdbd;
            outline: none;
            transition: border .24s ease-in-out;
        }
    </style>
@endpush

@push('javascript')

<script>
    $(document).ready(function(){

        window.dcmUri = (window.dcmUri || {});
        window.dcmUri = {
            resource  : '{!! route("dcm-app-resource.index") !!}',
            app_details  : '{!! route("app.details") !!}',
        }
    });
</script>

<script src="{{ asset('vendor/summernote/summernote-bs4.min.js') }}"></script>
<script src="{{ mix('js/admin-app-detail.js') }}"></script>
@endpush

