<div id="admin-configuration-registration"
data-props='@json(@$configurations)'
data-status='@json(@$select_options)'
data-title="Registration">
</div>

@push('stylesheet')

@endpush
@push('javascript')

<script src="{{ mix('js/admin-configuration-registration.js') }}"></script>

@endpush