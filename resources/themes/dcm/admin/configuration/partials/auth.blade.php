<div id="admin-configuration-authentication"
data-props='@json(@$configurations)'
data-status='@json(@$select_options)'
data-title="Authentication">
</div>

@push('stylesheet')

@endpush
@push('javascript')

<script src="{{ mix('js/admin-configuration-authentication.js') }}"></script>

@endpush