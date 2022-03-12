@extends( "layouts.master-admin")

@section('content')

@include('admin.dashboard.partials.navigation')

<div class="row animated fadeIn">
    <iframe frameborder="0"
    marginwidth="0" marginheight="0" align="top" scrolling="No" frameborder="0" hspace="0" vspace="0"
    width="100%" height="1000px" src={{ route('log-viewer::logs.list') }}></iframe>
</div>
@endsection