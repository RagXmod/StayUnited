@extends( "layouts.master-admin")

@section('content')
<div class="col-lg-12 col-xl-12 animated fadeIn">

    <div class="block">
        <div class="block-content">

            <ol class="breadcrumb breadcrumb-alt push">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.user.index') }}"><i class="fas fa-book-open"></i>  User Management</a>
                </li>
                <li class="breadcrumb-item active" aria-current="user">{{ $item->title ?? 'Create New User' }}</li>
            </ol>
            <div id="admin-user" data-props='@json($item)'
                    data-status='@json($statusCollections)'
                    data-roles='@json($getAllRoles)'
                    data-pageindex="{{ route("admin.user.index") }}">
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
                resource  : '{!! route("dcm-user-mgt-resource.index") !!}',
            }
        });
    </script>
    <script src="{{ asset('vendor/summernote/summernote-bs4.min.js') }}"></script>
    <script src="{{ mix('js/admin-usermgt.js') }}"></script>

@endpush
