@extends( "layouts.master")

@section('content')

<div class="row">

   <div class="col-lg-7 col-xl-7">
      <div class="block block-themed" itemscope itemtype="http://schema.org/MobileApplication">
         <div class="block-header bg-light">
            <h3 class="block-title"><a href="{{ url('/') }}">{{ __('dcm.home')}}</a> {{ __('dcm.breadcrumb_divider') }}
            <a href="#"> {{ __('dcm.categories_title') }}</a> {{ __('dcm.breadcrumb_divider') }}  {{ $category->title }}</h3>
         </div>

         <div class="block-content block-content-hover block-content-no-pad row mb-4">
               @if(isset($apps) && !$apps->isEmpty())
                     @foreach ($apps as $app)
                        @include('web.app.partials._app',['item' => $app ])
                     @endforeach

                     <div class="mb-4 mt-4 col-md-12" style="margin:auto 0; position:relative;">
                           {{ $apps->links() }}
                     </div>
               @else
                     <h3 class="block-title mb-4 text-center">
                        {{ __('dcm.no_app_connected_to_tags')}}
                     </h3>
               @endif
         </div>
         </div>
   </div>

   <div class="col-lg-5 col-xl-5">
      <div class="block block-rounded">
         <div class="block-content block-content-full bg-gd-lake-op text-center">
             <a class="item item-circle mx-auto bg-black-25" href="javascript:void(0)">
                 <i class="fa fa-2x fa-gamepad text-white"></i>
             </a>
             <p class="text-white font-size-h3 font-w300 mt-3 mb-0">
                 {{ __('dcm.category_menu_title') }}
             </p>
         </div>
         <div class="block-content block-content-full">
             <div class="block block-rounded">
                 @if(isset( $categories ))
                     @foreach($categories as $parent)

                        <div class="block block-rounded ">

                              <ul class="nav nav-tabs nav-tabs-block js-tabs-enabled" data-toggle="tabs" role="tablist">
                                 <li class="nav-item">
                                    <a class="nav-link" href="#">{{ $parent['title'] ?? ''  }}</a>
                                 </li>
                                 <li class="nav-item ml-auto">
                                    <a class="nav-link" href="#">
                                          <i class="fa fa-angle-right"></i>
                                    </a>
                                 </li>
                              </ul>
                              <div class="block-content tab-content">
                                 <div class="tab-pane active" id="btabs-static-home" role="tabpanel">
                                    @if(isset($parent['child_categories']))

                                       <ul class="list-unstyled row">
                                          @foreach($parent['child_categories'] as $category)
                                             @if ( @$category['parent_id'] == @$parent['id'])
                                                <li class="list-item col-6 py-3">
                                                   <a href="{{ $category['page_url'] }}" class="text-left">
                                                      <i class="{{ (isset($category['icon']) && $category['icon']) ? $category['icon'] : 'fab fa-google-play text-primary'}} mr-1"></i>
                                                      <strong>{{ $category['title']}} </strong>
                                                   </a>
                                                </li>
                                             @endif
                                          @endforeach
                                       </ul>
                                    @endif
                                 </div>
                              </div>
                        </div>
                     @endforeach
                 @else
                     <div>{{ __('dcm.no_categoriy_configured') }}</div>
                 @endif
             </div>
         </div>
     </div>
   </div>
</div>




@endsection


@push('javascript')

@endpush
