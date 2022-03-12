<div class="block">
    <div class="block-content block-content-full">
        <p class="text-uppercase font-w600 text-center mt-2 mb-4">
            Best Categories
        </p>

        @if(isset( $categories ))

                <ul class="nav nav-tabs nav-tabs-block" data-toggle="tabs" role="tablist">

                    @foreach($categories as $parentCategory)
                        <li class="nav-item @if($loop->first) active @endif">
                            <a class="nav-link @if($loop->first) active @endif" href="#cat-{{ $parentCategory['id']}}">{{ $parentCategory['title'] }}</a>
                        </li>
                    @endforeach
                </ul>
                <div class="block-content tab-content">

                    @foreach($categories as $parentCategory)
                        <div class="tab-pane @if($loop->first) active @endif" id="cat-{{ $parentCategory['id']}}" role="tabpanel">

                            @if ( isset($parentCategory['child_categories']))
                                <ul class="list-unstyled">
                                    @foreach($parentCategory['child_categories'] as $category)
                                        @if ( @$category['parent_id'] == @$parentCategory['id'])
                                                <li class="mb-3">
                                                    <a href="#" class="btn btn-block btn-outline-primary btn-sm text-left">
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