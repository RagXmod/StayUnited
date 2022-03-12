@extends( "layouts.master-admin")

@section('content')
<div class="col-lg-12 col-xl-12 animated fadeIn">
    @include('admin.app.partials.navigations',['navigationTitle' => 'Setup Shortcut Menu'])
    <div class="block block-bordered">
        <div class="block-content">
            <div class="row">
                <div class="col-lg-3">
                    <h2 class="content-heading">Site Configurations
                        <div>
                            <small class="text-muted">
                               Setup page.
                            </small>
                        </div>
                    </h2>
                </div>
                <div class="col-lg-9 col-xl-7">

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
        // window.dcmUri = {
        //     resource: '{!! route("dcm-configuration-resource.index") !!}',
        // }
    });
</script>

@endpush
