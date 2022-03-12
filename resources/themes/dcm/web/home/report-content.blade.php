@extends( "layouts.master")

@section('content')

<div class="row">

    <div class="col-md-12">
        <div class="block block-themed">
            <div class="block-header bg-light">
                <h3 class="block-title">
                    <a href="{{ route('web.home.index') }}">
                        {{ __('dcm.home') }}
                </a> » Submit a Report
                </h3>
            </div>


            <div class="block block-square ">
                <div class="block-header block-header-default">
                    <h3 class="block-title"> <i class="fa fa-fw fa-envelope-square mr-1"></i>Tell us your story.</h3>
                </div>
                <div class="block-content  px-3 py-3">

                    <form action="{{ route('web.home.reportcontent.post') }}" method="POST" enctype="multipart/form-data" >
                        @csrf
                        <div class="row push">
                            <div class="col-lg-4">
                                <p class="text-muted">
                                    Report Abusive Content
                                </p>
                            </div>
                            <div class="col-lg-8">
                                <div class="form-group">
                                    @if($errors->any())
                                        <div class="alert alert-danger text-left m-t-10">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                            <h3 class="alert-heading font-size-h4 my-2">Error</h3>
                                            @foreach ($errors->all() as $error)
                                                <p class="mb-0">{{ $error }}</p>
                                            @endforeach
                                        </div>

                                    @endif
                                    @if(session('success'))

                                        <div class="alert alert-success text-left m-t-10">
                                            <p><i class="fa fa-check"></i> {{ session('success') }}</p>
                                        </div>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label for="url">URL of inappropriate content or apps <small class="text-danger">*</small></label>
                                    <input type="text" class="form-control" id="url" name="url" value="{{ $report_url ?? '' }}" placeholder="URL of inappropriate content or apps">
                                </div>

                                <div class="form-group">
                                    <label for="name">Your name <small class="text-danger">*</small></label>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Your name">
                                </div>
                                <div class="form-group">
                                    <label for="email">Email <small class="text-danger">*</small></label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Your email">
                                </div>

                                <div class="form-group">
                                    <label for="subject">Subject</label>
                                    <input type="text" class="form-control" id="subject" name="subject" placeholder="Your subject">
                                </div>

                                <div class="form-group">
                                    <label for="reason">Reason for reporting: </label>
                                    <div class="radio">

                                        @if(isset($reason_items))

                                        @foreach($reason_items as $item)
                                            <div class="field">
                                                <div><input type="radio" name="reason" value="{{ $item['identifier'] }}" {{ ($item['selected'] === true) ? 'checked' : '' }}> {{ $item['label'] }}</div>
                                            </div>
                                        @endforeach
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="message">Any other things you wanna tell us? <small class="text-danger">*</small></label>
                                    <textarea class="form-control" id="message" name="message" rows="10" placeholder="Your question or comments..."></textarea>
                                </div>

                                @if( dcmConfig('is_recaptcha') == 'yes' && dcmConfig('recaptcha_on_report_content') == 'yes' && dcmConfig('recaptcha_site_key'))
                                    <div class="form-group ">
                                        <div class="g-recaptcha" data-sitekey="{{ dcmConfig('recaptcha_site_key') }}"></div>
                                    </div>
                                @endif

                                <div class="form-group">
                                    <button type="submit" class="btn btn-block btn-hero-primary">
                                        <i class="fa fa-fw fa-envelope mr-1"></i> Send
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>

    </div>

</div>

@stop

@push('javascript')
    @if( dcmConfig('is_recaptcha') == 'yes' && dcmConfig('recaptcha_on_report_content') == 'yes' && dcmConfig('recaptcha_site_key'))
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endif
@endpush