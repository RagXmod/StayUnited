<div class="block block-rounded">
    <div class="block-content block-content-full bg-gd-lake-op text-center">
        <a class="item item-circle mx-auto bg-black-25" href="javascript:void(0)">
            <i class="fa fa-2x fa-gamepad text-white"></i>
        </a>
        <p class="text-white font-size-h3 font-w300 mt-3 mb-0">
            {{ __('dcm.popular_categories') }}
        </p>
    </div>
    <div class="block-content block-content-full">

        <div class="block block-rounded block-bordered">

            @if(isset( $sidebar['categories'] ))

                <ul class="nav nav-tabs nav-tabs-block" data-toggle="tabs" role="tablist">

                    @foreach($sidebar['categories'] as $parentCategory)
                        <li class="nav-item @if($loop->first) active @endif">
                            <a class="nav-link @if($loop->first) active @endif" href="#cat-{{ $parentCategory['id']}}">{{ $parentCategory['title'] }}</a>
                        </li>
                    @endforeach
                </ul>
                <div class="block-content tab-content">

                    @foreach($sidebar['categories'] as $parentCategory)
                        <div class="tab-pane @if($loop->first) active @endif" id="cat-{{ $parentCategory['id']}}" role="tabpanel">

                            @if ( isset($parentCategory['child_categories']))
                                <ul class="list-unstyled">
                                    @foreach($parentCategory['child_categories'] as $category)
                                        @if ( @$category['parent_id'] == @$parentCategory['id'])
                                                <li class="mb-3">
                                                    <a href="{{ $category['page_url'] }}" class="btn btn-block btn-outline-primary btn-sm text-left">
                                                            <i class="{{ (isset($category['icon']) && $category['icon']) ? $category['icon'] : 'fab fa-google-play text-primary'}} mr-1"></i>
                                                            <strong>{{ $category['title']}} </strong>
                                                    </a>
                                                    </li>
                                        @endif
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    @endforeach
                </div>

            @else
                <div>{{ __('dcm.no_categoriy_configured') }}</div>
            @endif


        </div>


    </div>
</div>