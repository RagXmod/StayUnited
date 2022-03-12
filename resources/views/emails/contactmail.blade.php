@component('mail::layout')
{{-- Header --}}
@slot('header')
    @component('mail::header', ['url' => url('/') ])
    Contact us Form - {{ dcmConfig('site_name') }}
    @endcomponent
@endslot

{{-- Body --}}
Client Name: {{ $name }},

Email Address: {{ $email }},

@if($subject)
Subject: {{ $reason }}

@endif

@if($reason)
Reason for contact: **{{ $reason }}**

@endif



Message:


> {{ $message ?? '' }}




Cheers,

SystemBot

{{-- Footer --}}
@slot('footer')
    @component('mail::footer')
        © {{ date('Y') }} {{ dcmConfig('site_name') }}. @lang('All rights reserved.')
    @endcomponent
@endslot
@endcomponent