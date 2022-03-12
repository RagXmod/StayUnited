<?php

/**
 * Module Core: App\Http\Controllers\Web\Home\ContactUsController
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
use App\Mail\ContactMail;

use SEOMeta;
use OpenGraph;
use Twitter;

class ContactUsController extends BaseController
{

    public function getContactUs() {



            $title = __('dcm.contact_contactus_lbl') . ' - '. dcmConfig('site_name');
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
            'reason_items' => $this->contactUsReason()
        ];
        return view('web.home.contactus')->with($data);
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function postContactUs(Request $request)
    {

        $this->validate($request, [
            'name'    => 'required|min:2',
            'email'   => 'required|email',
            'message' => 'required|min:5',
            'reason'  => 'required'
        ]);

        Mail::send( new ContactMail($request));
        session()->flash('success', 'Successfully sent message to us.. We will get back to you!');
        return redirect()->route('web.home.contactus');
    }

    public function contactUsReason() {

        return cache()->remember('contact_us_reason', 1440, function () {
            $itemArr = [
                'Comments and feedback',
                'Report a problem',
                'DMCA takedown request',
                'Developer Support and Feedback',
                'Others'
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
