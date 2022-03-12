<div id="admin-configuration-app"
data-props='@json(@$configurations)'
data-title="App">
</div>

@push('stylesheet')

<link rel="stylesheet" id="css-theme" href="{{ mix('css/codemirror-custom.css') }}">

@endpush
@push('javascript')

<script src="{{ mix('js/admin-configuration-app.js') }}"></script>

@endpush