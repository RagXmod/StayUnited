<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Request;

class ReportAppMail extends Mailable
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

        $siteTitle  = dcmconfig('site_name') ?? 'Report Content';
        $sendToMail = dcmconfig('contact_email') ?? 'demo-demogoogelplaystore@mailinator.com';

        $data = array_only($this->request->all(), ['name', 'email','message','subject', 'reason', 'url']);

        $itemReasonArr = cache('report_content_reason');

        $defaultReason = 'Pornographic content';
        if ( !isset($data['reason']) )
            $data['reason'] = str_slug($defaultReason,'_');

        if ( $itemReasonArr && isset($itemReasonArr[ $data['reason'] ])) {

            $reason = $itemReasonArr[ $data['reason'] ];
            $data['reason'] = $reason['label'] ?? $defaultReason;
        }

        return $this->subject("Howdy {$siteTitle}, you got a report content message!")
            ->from( $this->request->email, $this->request->name )
            ->to($sendToMail, $siteTitle)
            ->markdown('emails.reportappmail')
            ->with($data);
    }
}
