@extends( "layouts.master-admin")

@section('content')
<div class="col-lg-12 col-xl-12 animated fadeIn">
    @include('admin.app.partials.navigations',['navigationTitle' => 'Setup Shortcut Menu'])
    <div class="block block-bordered">
        <div class="block-content">
            <div class="row">

                <div class="col-lg-12 mb-4" >

                   <div class="row">
                        <div class="col-md-6">
                            <h3 class="h4 mt1">Available Ads</h3>
                            <ul class='list-unstyled  nested_with_switch source_list' >
                                @foreach($ads_collections as $index => $ads)
                                    <li class="list-group-item" data-id="{{ $ads['id'] }}" data-name='{{ $ads['title'] }}'>{{ $ads['title'] }}</li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="col-md-6 ">
                           <h3 class="h4 mt1">Assign Ads Block</h3>
                            <ul class='list-unstyled  list-group nested_with_switch copy-list' style="width:100%;">

                                @foreach($ads_placement_collections as $index => $item)
                                     <li  class="list-group-item" data-id="{{ $item['id'] }}" data-name='{{ $item['title'] }}' >
                                        <p> {{ $item['title'] }}</p>
                                        <ul class='list-unstyled  nested_with_switch source_list my-2' >
                                            @if( isset($item['ads'])  && count($item['ads']) > 0)
                                                @foreach($item['ads'] as $_index => $_item)

                                                    <li  class="list-group-item" data-id="{{ $_item['id'] }}" data-name='{{ $_item['title'] }}' >
                                                            <p> {{ $_item['title'] }}</p>
                                                    </li>
                                                @endforeach
                                            @endif
                                        </ul>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="col-lg-12 mt-2">
                                <button type="button"  id="save-ads" class="btn btn-block btn-success"> Save </button>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</div>


@endsection
@push('stylesheet')
   <style>

            body.dragging,body.dragging * {
            cursor:move!important;
            }

            .dragged {
            position:absolute;
            top:0;
            opacity:0.5;
            z-index:2000;
            }

            ol.vertical {
            min-height:10px;
            margin:0 0 9px;
            }

            ol.vertical li {
            display:block;
            border:1px solid #ccc;
            color:#08c;
            background:#eee;
            margin:5px;
            padding:5px;
            }

            ol.vertical li.placeholder {
            position:relative;
            border:none;
            margin:0;
            padding:0;
            }

            ol.vertical li.placeholder:before {
            position:absolute;
            content:"";
            width:0;
            height:0;
            margin-top:-5px;
            left:-5px;
            top:-4px;
            border:5px solid transparent;
            border-left-color:red;
            border-right:none;
            }

            ol {
            list-style-type:none;
            }

            ol li.highlight {
            background:#333;
            color:#999;
            }

            ol li.highlight i.icon-move {
            background-image:url(../img/glyphicons-halflings-white.png);
            }

            ol.nested_with_switch,ol.nested_with_switch ol {
            border:1px solid #eee;
            }

            ol.nested_with_switch.active,ol.nested_with_switch ol.active {
            border:1px solid #333;
            }

            ol.simple_with_animation {
            border:1px solid #999;
            }

            .switch-container {
            display:block;
            margin-left:auto;
            margin-right:auto;
            width:80px;
            }

            .navbar-sort-container {
            height:200px;
            }

            ol.nav .divider-vertical {
            cursor:default;
            }

            ol.nav li.dragged {
            background-color:#2c2c2c;
            }

            ol.nav li.placeholder {
            position:relative;
            }

            ol.nav ol.dropdown-menu li.placeholder:before {
            border:5px solid transparent;
            border-left-color:red;
            margin-top:-5px;
            margin-left:none;
            top:0;
            left:10px;
            border-right:none;
            }

            .sorted_table tr.placeholder {
            display:block;
            background:red;
            position:relative;
            border:none;
            margin:0;
            padding:0;
            }

            .sorted_table tr.placeholder:before {
            content:"";
            position:absolute;
            width:0;
            height:0;
            border:5px solid transparent;
            border-left-color:red;
            margin-top:-5px;
            left:-5px;
            border-right:none;
            }

            .sorted_head th.placeholder {
            display:block;
            background:red;
            position:relative;
            width:0;
            height:0;
            margin:0;
            padding:0;
            }

            ol i.icon-move,ol.nested_with_switch li,ol.simple_with_animation li,ol.serialization li,ol.default li,ol.nav li,ol.nav li a,.sorted_table tr,.sorted_head th {
            cursor:pointer;
            }

            ol.nav li.placeholder:before,.sorted_head th.placeholder:before {
            content:"";
            position:absolute;
            width:0;
            height:0;
            border:5px solid transparent;
            border-top-color:red;
            top:-6px;
            margin-left:-5px;
            border-bottom:none;
            }

            .list-group-item:first-child {
               border-top-left-radius: 0px;
               border-top-right-radius: 0px;
            }

            ul.source_list {
                border: dotted 3px #d1d1d2;
                padding: 5px;
            }


    </style>
@endpush

@push('javascript')
<script type="text/javascript" src="{{ asset('vendor/sortable/jquery-sortable-min.js') }}"></script>
<script>
    $(document).ready(function(){

        window.dcmUri = (window.dcmUri || {});
        window.dcmUri = {
            resource: '{!! route("dcm-setup-ads-resource.store") !!}',
        };

        var oldContainer;
            var group = $("ul.source_list").sortable({
            group: 'nested',
            afterMove: function (placeholder, container) {
                if(oldContainer != container){
                    if(oldContainer)
                        oldContainer.el.removeClass("active");
                    container.el.addClass("active");

                    oldContainer = container;
                }
            },
            onDrop: function ($item, container, _super) {
                    container.el.removeClass("active");
                    _super($item, container);
                }
            });

            $("ul.copy-list").sortable({
                group:  'nested',
                drop: false,
                drag: false,
            });
            $("ul.copy-list>li>ul").sortable({
                group:  'nested',
                drop: true,

            });

            $(".switch-container").on("click", ".switch", function  (e) {
                var method = $(this).hasClass("active") ? "enable" : "disable";
                $(e.delegateTarget).next().sortable(method);
            });



            $('#save-ads').click(function(){
                var data =  $("ul.copy-list").sortable("serialize").get();



                axios.post( window.dcmUri['resource'] , data)
                .then(function (response) {
                    console.log(response);
                    window.location.reload();
                })
                .catch(function (error) {
                    console.log(error);
                });

                console.log(data);
            });
    });
</script>

@endpush
