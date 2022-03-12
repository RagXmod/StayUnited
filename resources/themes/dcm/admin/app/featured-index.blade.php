@extends( "layouts.master-admin")

@section('content')
<div class="col-lg-12 col-xl-12 animated fadeIn">

    @include('admin.app.partials.navigations')

    <div class="block block-rounded block-bordered">
        <div class="block-header block-header-default">
           <h3 class="block-title">
               <i class="fab fa-android mr-2"></i> Featured App Collections
            </h3>
            <a href="{{ route('admin.featured.app.create') }}" class="btn btn-success btn-xs">
                <i class="fa fa-file-alt mr-2"></i> Add New Featured Apps
            </a>
        </div>
        <div class="block-content">
            <div class="table-responsive mt-5 mb-5">
                <table class="table table-striped table-bordered " id="page-table">
                    <thead>
                        <tr>
                            <th>Featured app title</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
     </div>


    </div>
</div>


<div id="deleteModal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

        <div class="modal-body">
            <p class="description">Are you sure you want to delete this featured app collections [<strong><span id="itemId"></span></strong>]?</p>
            <div class="hasError text-danger d-none"></div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-sm btn-danger" id="confirm_delete"> Delete</button>
        </div>
        </div>
    </div>
</div>


@endsection

@push('stylesheet')
    @include('common.datatable-bootstrap-css')
    <style>
        .modal-dialog {
            padding-top: 15%;
        }
    </style>
@endpush
@push('javascript')
<script src="{{ asset('vendor/datatable/js/datatables.min.js') }}"></script>
<script>
    $(document).ready(function(){

        window.dcmUri = (window.dcmUri || {});
        window.dcmUri = {
            resource: '{!! route("dcm-featured-app-resource.index") !!}',
        }
    });
</script>
<script src="{{ mix('js/admin/page.js') }}"></script>
@endpush
