@extends( "layouts.master")

@section('content')

<div class="row">

   <div class="col-md-12">
      <div class="block block-themed">
         <div class="block-header bg-light">
            <h3 class="block-title">
                <a href="{{ route('web.home.index') }}">
                    {{ __('dcm.home') }}
               </a> »
               <a href="{{ route('web.page.index') }}">
                    {{ __('dcm.page') }}
               </a>
               » All Pages
            </h3>
         </div>
         <div class="block block-square ">
               <div class="block-header block-header-default">
                  <h3 class="block-title"> <i class="fa fa-fw fa-link mr-1"></i> All Pages</h3>
               </div>
               <div class="block-content  px-3 py-3">

                     <table class="js-table-checkable table table-hover table-vcenter js-table-checkable-enabled">
                           <thead>
                               <tr>
                                   <th>Name</th>
                                   <th class="d-none d-sm-table-cell" style="width: 15%;">Link</th>
                               </tr>
                           </thead>
                           <tbody>
                              @if( isset($pages) )
                                 @foreach ($pages as $item)
                                    <tr>
                                       <td>
                                             <p class="font-w600 mb-1">
                                                <a href="{{ $item['link'] ?? '' }}">
                                                   <i class="{{ $item['icon'] ?? ''}} mr-1"></i> {{ $item['title'] ?? '' }}
                                                </a>
                                             </p>

                                       </td>
                                       <td class="d-none d-sm-table-cell">
                                             <a href="{{ $item['link'] ?? '' }}" class="btn btn-sm btn-secondary">
                                                Visit Page
                                             </a>
                                       </td>
                                    </tr>
                                 @endforeach
                              @endif
                           </tbody>
                       </table>
               </div>
         </div>

      </div>

   </div>

</div>




@endsection


@push('javascript')

@endpush