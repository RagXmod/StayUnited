@extends( "layouts.master-admin")

@section('content')
<div class="col-lg-12 col-xl-12 animated fadeIn">
    @include('admin.app.partials.navigations',['navigationTitle' => 'Setup Shortcut Menu'])
    <div class="block block-bordered">
        <div class="block-content">
            <div class="row">
                <div class="col-lg-3">
                    <h2 class="content-heading">Slider Image Configurations
                        <div>
                            <small class="text-muted">
                               Setup slider images.
                            </small>
                        </div>
                    </h2>
                </div>
                <div class="col-lg-9">
                    <div id="admin-setup-slider">
                    </div>
                    <div class="form-group">
                        <label htmlFor="app_link"> Image sliders</label>
                        <table id="image-sliders" class="table table-striped ">
                            <thead>
                                <tr>
                                    <th width="">Image</th>
                                    <th width="">Link</th>
                                    <th width="180">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($sliders))
                                @foreach($sliders as $slider)
                                    <tr data-sliderId="{{ $slider['id'] }}">
                                        <td>
                                            <img src="{{ $slider['image_url'] }}" class="img-responsive" width="50"/>
                                        </td>
                                        <td>
                                            <span>{{ str_limit($slider['link'], 50) }}</span>
                                        </td>
                                        <td>
                                            {{-- <button type="button" class="btn btn-sm btn-success mr-1 mb-2">
                                                <i class="fa fa-edit">
                                                </i> Edit
                                            </button> --}}
                                            <button type="button" class="btn btn-sm btn-danger mb-2 btndelete">
                                                <i class="fa fa-trash">
                                                </i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach

                                @else

                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection



@push('stylesheet')
    <style>
        .dropzone {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 15px;
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
            resource: '{!! route("dcm-setup-slider-resource.store") !!}',
        };

        $('#image-sliders').on('click','.btndelete',function(){

            var that = this;
            var tbleId =  $(this).parents('tr').data('sliderid');
            axios.delete( window.dcmUri['resource'] + '/' + tbleId)
            .then(function (response) {
                $(that).parents('tr').remove();
            })
            .catch(function (error) {
                console.log(error);
            });
        });
    });
</script>
<script src="{{ mix('js/admin-setup-slider.js') }}"></script>
@endpush
