<?php

/**
 * Module Core: App\Http\Controllers\Web\Home\ReportAbuseContentController
 *
 * Long description for class (if any)...
 *
 * @package    DCM
 * @author     Anthony Pillos <dev.anthonypillos@gmail.com>
 * @copyright  2018 (c) DCM
 * @version    Release: v1.0.0
 * @link       http://devcorpmanila.com
 */

namespace App\Http\Controllers\Web\Home;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Modules\Core\Http\Controllers\BaseController;
use App\Mail\ReportAppMail;

use SEOMeta;
use OpenGraph;
use Twitter;

class ReportAbuseContentController extends BaseController
{

    public function getReportContent(Request $request) {


        $title = __('dcm.contact_report_abuse_content_lbl') . ' - '. dcmConfig('site_name');
            $desc  = str_limit(($title) , 160);
            $url = route('web.home.contactus');
            $logo     = dcmConfig('site_logo');

            SEOMeta::setTitle($title)
                        ->setDescription( $desc )
                        ->setCanonical( $url );


            SEOMeta::addKeyword( explode('-', str_slug($title) ));


            SEOMeta::addMeta('article:published_time', now()->toW3CString(), 'property');
            SEOMeta::addMeta('article:modified_time', now()->toW3CString(), 'property');
            SEOMeta::addMeta('article:section', $title, 'property');
            SEOMeta::addMeta('article:tag', $title, 'property');

            OpenGraph::setDescription($desc);
            OpenGraph::setTitle($title);
            OpenGraph::setUrl( $url );
            OpenGraph::addProperty('type', 'article');
            OpenGraph::addProperty('locale', app()->getLocale());
            OpenGraph::addProperty('locale:alternate', ['en-us']);
            OpenGraph::addImage($logo );


            // // You can chain methods
            Twitter::setType('article')
                        ->setImage($logo )
                        ->setTitle($title)
                        ->setDescription($desc)
                        ->setUrl( $url )
                        ->setSite($title);

        $data = [
            'reason_items' => $this->reportContentReason(),
            'report_url'   => $request->has('report-url') ? route('web.app.detail', $request->get('report-url')) : ''
        ];
        return view('web.home.report-content')->with($data);
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function postReportContent(Request $request)
    {

        $this->validate($request, [
            'name'    => 'required|min:2',
            'email'   => 'required|email',
            'message' => 'required|min:5',
            'reason'  => 'required'
        ]);

        Mail::send( new ReportAppMail($request));
        session()->flash('success', 'Successfully sent message to us.. We will get back to you!');
        return redirect()->route('web.home.reportcontent');
    }

    public function reportContentReason() {

        return cache()->remember('report_content_reason', 1440, function () {
            $itemArr = [
                'Pornographic content',
                'Violence or horror related content',
                'Hateful or abusive content',
                'Illegal prescription or other drugs related content',
                'Virus or malware',
                'IP Infringement',
                'Fake apps or version error'
            ];

            $_collect = [];
            foreach($itemArr as $index => $item) {

                $identifier = str_slug($item, '_');
                $_collect[$identifier] = [
                    'identifier' => $identifier,
                    'label'      => $item,
                    'selected'   => ($index == 0) ? true : false,
                ];
            }
            return $_collect;
        });
    }
}
