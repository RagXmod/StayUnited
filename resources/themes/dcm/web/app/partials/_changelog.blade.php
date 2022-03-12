@if(isset($latest_version) && $latest_version)
<div class="block block-square">
      <div class="block-header block-header-default">
         <h3 class="block-title"> <i class="fa fa-fw fa-calendar-alt mr-1"></i> {{ __('dcm.latest_version_changelog_title',['attr' => $app->title, 'version' => $latest_version]) }}</h3>
      </div>
      <div class="block-content px-0 pt-0">
         <div class="content 3" itemprop="description">
            <h6><span class="badge badge-secondary badge-pill">{{ __('dcm.latest_version_changelog_date_title', ['attr' => $latest_version_created_at]) }}</span></h6>
            <div>
               {!! nl2br($latest_version_description) !!}
            </div>
         </div>
      </div>
</div>
@endif