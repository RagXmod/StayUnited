<div id="admin-configuration-analytics"
data-props='@json(@$configurations)'
data-title="Analytics">
</div>

@push('stylesheet')

<link rel="stylesheet" id="css-theme" href="{{ mix('css/codemirror-custom.css') }}">

@endpush
@push('javascript')

<script src="{{ mix('js/admin-configuration-analytics.js') }}"></script>

@endpush