@extends( "layouts.master-admin")

@section('content')


@include('admin.app.partials.navigations')
<div className="row">


    <div id="admin-app"  data-letters='@json($letters)'></div>
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


    </style>
@endpush
@push('javascript')

<script>
    window.dcmUri = (window.dcmUri || {});
    window.dcmUri = {
        resource  : '{!! route("dcm-app-resource.index") !!}',
    }
</script>
<script src="{{ mix('js/admin-app.js') }}"></script>
@endpush
