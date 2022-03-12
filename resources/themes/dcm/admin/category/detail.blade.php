@extends( "layouts.master-admin")

@section('content')
<div class="col-lg-12 col-xl-12 animated fadeIn">

    <div class="block">
        <div class="block-content">

            <ol class="breadcrumb breadcrumb-alt push">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.category.index') }}"><i class="fas fa-book-open"></i> Categories</a>
                </li>
                <li class="breadcrumb-item active" aria-current="category">{{ $item->title ?? 'Create New Category' }}</li>
            </ol>
            <div id="admin-category"
                data-status='@json($statusCollections)'
                data-props='@json($item)'
                data-pageindex="{{ route("admin.category.index") }}">
            </div>
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
                resource  : '{!! route("dcm-category-resource.index") !!}',
            }
        });
    </script>
    <script src="{{ asset('vendor/summernote/summernote-bs4.min.js') }}"></script>
    <script src="{{ mix('js/admin-category.js') }}"></script>

@endpush
