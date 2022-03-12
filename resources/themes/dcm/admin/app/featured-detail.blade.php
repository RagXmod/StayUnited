@extends( "layouts.master-admin")

@section('content')

<div class="col-lg-12 col-xl-12 animated fadeIn">

    @include('admin.app.partials.navigations')

    <div class="block">
        <div class="block-content">

            <ol class="breadcrumb breadcrumb-alt push">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.featured.apps') }}"><i class="fab fa-android mr-2"></i> Featured Apps</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">{{ $item['title'] ?? 'Create featured app collections' }}</li>
            </ol>
           
            <div id="admin-featured-app"
                data-props='@json($item ?? [])'
                data-status='@json($statusCollections)'
                data-pageindex="{{ route("admin.featured.apps") }}"
            >
            </div>
        </div>
    </div>
</div>
@endsection

@push('stylesheet')
    <link rel="stylesheet" href="{{ asset('vendor/summernote/summernote-bs4.css') }}">
    <style>
        /* .search_apps ul li{
            float: left;
            width: 50%;//helps to determine number of columns, for instance 33.3% displays 3 columns
        }
        .search_apps ul{
            list-style-type: disc;
        } */

        .search_apps ul{

        }
        .search_apps li{
            float:left;
            width:50%;
        }
        .search_apps li:nth-child(even){
            margin-right:2px;
        }

    </style>
@endpush
@push('javascript')

<script>
    window.dcmUri = (window.dcmUri || {});
    window.dcmUri = {
        app_resource  : '{!! route("dcm-app-resource.index") !!}',
        resource  : '{!! route("dcm-featured-app-resource.index") !!}',
    }
</script>
<script src="{{ asset('vendor/summernote/summernote-bs4.min.js') }}"></script>
<script src="{{ mix('js/admin-featured-app.js') }}"></script>
@endpush
