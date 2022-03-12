<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Request;

class ContactMail extends Mailable
{
    use Queueable, SerializesModels;

    public $request;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {

        $this->request = $request;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = sprintf("[%s] %s", dcmconfig('site_name'), __('New User Registration'));

        $siteTitle  = dcmconfig('site_name') ?? 'Contact us form';
        $sendToMail = dcmconfig('contact_email') ?? 'demo-googelplaystore@mailinator.com';

        $data = array_only($this->request->all(), ['name', 'email','message','subject', 'reason']);

        $contactCacheReasonArr = cache('contact_us_reason');

        $defaultReason = 'Comments and Feedback';
        if ( !isset($data['reason']) )
            $data['reason'] = str_slug($defaultReason,'_');

        if ( $contactCacheReasonArr && isset($contactCacheReasonArr[ $data['reason'] ])) {

            $reason = $contactCacheReasonArr[ $data['reason'] ];
            $data['reason'] = $reason['label'] ?? $defaultReason;
        }

        return $this->subject($subject)
            ->from( $this->request->email, $this->request->name )
            ->to($sendToMail, $siteTitle)
            ->markdown('emails.contactmail')
            ->with($data);
    }
}
