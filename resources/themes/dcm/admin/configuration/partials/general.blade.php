<div id="admin-configuration-general"
data-props='@json(@$configurations)'
data-timezonelist='@json(@$timezonelist)'
data-countries='@json(@$countries)'
data-languages='@json(@$languages)'
data-status='@json(@$select_options)'
data-title="General">
</div>

@push('stylesheet')
    <style>
        .dropzone {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
            border-width: 2px;
            border-radius: 2px;
            border-color: #eeeeee;
            border-style: dashed;
            background-color: #fafafa;
            color: #bdbdbd;
            outline: none;
            transition: border .24s ease-in-out;
        }
    </style>
@endpush
@push('javascript')

<script src="{{ mix('js/admin-configuration-general.js') }}"></script>

@endpush