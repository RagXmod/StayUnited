@extends( "layouts.master")

@section('content')

<div class="row">

    @include('web.home.partials.slider')
    {{-- ads start --}}
    @include('common.ads-placement',[ 'identifier' => 'homepage-leaderboard'])
    {{-- ads end --}}
    @include('web.home.partials.featured-apps')
    @include('web.home.partials.newest-apps')

</div>

@endsection

@push('javascript')

@endpush
