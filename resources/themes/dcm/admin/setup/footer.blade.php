@extends( "layouts.master-admin")

@section('content')
<div class="col-lg-12 col-xl-12 animated fadeIn">
    @include('admin.app.partials.navigations',['navigationTitle' => 'Setup Shortcut Menu'])
    <div class="block block-bordered">
        <div class="block-content">
            <div class="row">
                <div class="col-lg-5">
                    <div class="card-header bg-dark text-white">Create / Update Footer</div>
                    <div class="card-body">
                        <form id="frmEdit" class="form-horizontal">
                            <div class="form-group">
                                <label for="text">Text</label>
                                <div class="input-group">
                                    <input type="text" class="form-control item-menu" name="text" id="text" placeholder="Text">
                                    <div class="input-group-append">
                                        <button type="button" id="myEditor_icon" class="btn btn-outline-secondary"></button>
                                    </div>
                                </div>
                                <input type="hidden" name="icon" class="item-menu">
                            </div>
                            <div class="form-group">
                                <label for="href"> Select url from existing pages</label>
                                <select id="pages" class="form-control">
                                    @if( isset($pages) && $pages)
                                        <option value=""> Select pages</option>
                                        @foreach($pages as $page)
                                            <option value="{{ $page['link'] ?? '/' }}">{{ $page['title'] ?? 'N/A' }}</option>
                                        @endforeach
                                    @else
                                        <option value="">No pages found</option>
                                    @endif
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="href"> Select url from existing categories</label>

                                <select id="category" class="form-control">
                                    @if( isset($categories) && $categories)
                                        <option value=""> Select category link</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category['page_url'] ?? '/' }}">{{ $category['title'] ?? 'N/A' }}</option>
                                        @endforeach
                                    @else
                                        <option value="">No category found</option>
                                    @endif
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="href">URL</label>
                                <input type="text" class="form-control item-menu" id="href" name="href" placeholder="URL">
                            </div>
                            <div class="form-group">
                                <label for="target">Target</label>
                                <select name="target" id="target" class="form-control item-menu">
                                    <option value="_self">Self</option>
                                    <option value="_blank">Blank</option>
                                    <option value="_top">Top</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="title">Tooltip</label>
                                <input type="text" name="title" class="form-control item-menu" id="title" placeholder="Tooltip">
                            </div>
                        </form>

                        <button type="button" id="btnUpdate" class="btn btn-block btn-primary" disabled><i class="fas fa-sync-alt"></i> Update</button>
                        <button type="button" id="btnAdd" class="btn btn-block btn-success"><i class="fas fa-plus"></i> Add</button>
                    </div>
                </div>
                <div class="col-lg-7 ">
                    <div class="card mb-3">
                        <div class="card-header bg-dark text-white">Arrange Home Footer</div>
                        <div class="card-body">
                            <ul id="myEditor" class="sortableLists list-group">
                            </ul>
                        </div>
                    </div>

                    <button id="btnOutput" type="button" class="btn btn-block btn-success">
                         <i class="fas fa-save"></i> Save
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection

@push('stylesheet')
    <link rel="stylesheet" href="{{ asset('vendor/menueditor/bootstrap-iconpicker/css/bootstrap-iconpicker.min.css') }}">
@endpush

@push('javascript')
<script type="text/javascript" src="{{ asset('vendor/menueditor/editor.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('vendor/menueditor/bootstrap-iconpicker/js/iconset/fontawesome5-3-1.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('vendor/menueditor/bootstrap-iconpicker/js/bootstrap-iconpicker.min.js') }}"></script>
<script>
    jQuery(document).ready(function () {

        window.dcmUri = (window.dcmUri || {});
        window.dcmUri = {
            resource: '{!! route("dcm-setup-footer-resource.store") !!}',
        };

        var arrayjson = '{!! fixedJson($menus ?? []) !!}'

        // icon picker options
        var iconPickerOptions = {searchText: "Search Font...", labelHeader: "{0}/{1}"};
        // sortable list options
        var sortableListOptions = {
            placeholderCss: {'background-color': "#cccccc"},
            opener: {
                 active: true,
            }
        };

        var editor = new MenuEditor('myEditor', {listOptions: sortableListOptions, iconPicker: iconPickerOptions});
            editor.setForm($('#frmEdit'));
            editor.setUpdateButton($('#btnUpdate'));
            editor.setData(arrayjson);

            $('#btnOutput').on('click', function () {
                var str = editor.getString();
                // console.log('str', JSON.parse(str));

                axios.post( window.dcmUri['resource'] , JSON.parse(str))
                .then(function (response) {
                    // console.log(response);
                    window.location.reload();
                })
                .catch(function (error) {
                    console.log(error);
                });


            });

            $("#btnUpdate").click(function(){
                editor.update();
            });

            $('#btnAdd').click(function(){
                editor.add();
            });


            $('#pages, #category').change(function() {
                //Use $option (with the "$") to see that the variable is a jQuery object
                var $option = $(this).find('option:selected');
                //Added with the EDIT
                var value = $option.val();//to get content of "value" attrib
                $('#href').val( value);
            });
    });
</script>

@endpush
