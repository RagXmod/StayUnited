@extends('installation::layouts.master')

@section('content')

    @include('installation::steps', ['steps' => [
        'welcome' => 'selected done',
        'requirements' => 'selected done',
        'permissions' => 'selected done',
        'database' => 'selected done',
        'installation' => 'selected'
    ]])

    <form action="{{ route('dcm.install.processing') }}" method="POST" >
        @csrf
        <div class="step-content">
            <h3>Installation</h3>
            <hr>
            <p>{{ config('app.name') }} is ready to be installed!</p>
            <p>Before you proceed, please provide the name for your application below:</p>
            <div class="form-group">
                <label for="app_name">App Name</label>
                <input type="text" class="form-control" id="app_name" name="app_name" value="{{ config('app.name') }}">
            </div>
            <div class="form-group">
                <label for="app_name">App Url</label>
            <input type="text" class="form-control" id="app_url" name="app_url" placeholder="Ex. http://yourdomainname.com" value="{{ url('/') }}">
            </div>
            <button class="btn btn-green pull-right" data-toggle="loader" data-loading-text="Installing" type="submit">
                <i class="fa fa-play"></i>
                Install
            </button>
            <div class="clearfix"></div>
        </div>
    </form>
@stop