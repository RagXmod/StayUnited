@extends( "layouts.master-admin")

@section('content')

<div class="col-lg-12 col-xl-12 animated fadeIn">

    @include('admin.app.partials.navigations')

    <div class="block">
        <div class="block-content">

            <ol class="breadcrumb breadcrumb-alt push">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.featured.apps') }}"><i class="fab fa-android mr-2"></i> Create apps via playstore</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">{{ $item['title'] ?? 'Create Apps' }}</li>
            </ol>

            <div id="admin-app-store"
                data-props='@json($item ?? [])'
                data-letters='@json($letters ?? [])'
                data-categories='@json($categoryCollections)'
            >
            </div>
        </div>
    </div>
</div>

@endsection


@push('stylesheet')
    <style>

        #admin-app .page-link:focus {
            box-shadow: 0 0 0 0;
        }
        .btn-circle {
            width: 30px;
            height: 30px;
            text-align: center;
            padding: 6px 0;
            font-size: 12px;
            line-height: 1.428571429;
            border-radius: 0px;
            border: 1px solid #eaeaea;
            margin:5px;
        }

        .search_apps ul{
            columns        : 2;
            -webkit-columns: 2;
            -moz-columns   : 2;
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
        app_search    : '{!! route("app.search") !!}',
        api_app_create: '{!! route("api.app.create") !!}',
    }
</script>
<script src="{{ mix('js/admin-app-store.js') }}"></script>
@endpush
