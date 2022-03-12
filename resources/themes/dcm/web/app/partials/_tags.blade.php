@if(isset($app->tags) && !$app->tags->isEmpty())
<div class="block block-square ">
    <div class="block-header block-header-default">
       <h3 class="block-title"> <i class="fa fa-fw fa-tags mr-1"></i> {{ __('dcm.additional_tag_title', ['attr' => $app->title]) }} </h3>
    </div>
    <div class="block-content  px-3 py-3">
          @foreach($app->tags as $tag)
             <a class="badge badge-sm badge-secondary text-uppercase font-w600"
                href="{{ route('web.app.tag.detail', $tag->slug) }}">
                {{ $tag->name }}
             </a>
          @endforeach
    </div>
</div>
@endif