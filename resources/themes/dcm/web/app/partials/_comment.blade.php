<div class="col-md-12 mt-5">
    <div class="block block-themed">
        <div class="block-header bg-light">
            <h3 class="block-title"><i class="fa fa-comments"></i> {{ __('dcm.post_comment_title') }}</h3>
            <div class="block-options">
            </div>
        </div>
        <div class="block-content mb-4">
            @comments(['model' => $app ?? [] ])
            @endcomments
        </div>
    </div>
</div>