<?php

/**
 * Module Core: App\Http\Controllers\Web\User\ProfileController
 *
 * Long description for class (if any)...
 *
 * @package    DCM
 * @author     Anthony Pillos <dev.anthonypillos@gmail.com>
 * @copyright  2018 (c) DCM
 * @version    Release: v1.0.0
 * @link       http://devcorpmanila.com
 */

namespace App\Http\Controllers\Web\User;

use Illuminate\Http\Request;
use Modules\Core\Traits\ResponseTrait;
use Modules\User\Http\Requests\UpdateRequest;
use Modules\Core\Http\Controllers\BaseController;
use Modules\User\Eloquent\Repositories\UserRepositoryEloquent;
use Exception;

use SEOMeta;
use OpenGraph;
use Twitter;

class ProfileController extends BaseController
{
    use ResponseTrait;

    private $userModel;

    public function __construct(UserRepositoryEloquent $user) {

        parent::__construct();
        $this->userModel = $user;
    }

    /**
     * View Current User Profile
     *
     * @return Illuminate\Support\Facades\View
     */
    public function getProfile()
    {
        $user       = $this->auth->user();
        $myGravatar = $user->getMyGravatar();

        $data = compact('user','myGravatar');

        $data = array_merge($data, [
            'has_sidebar' => false,
            'post_update_avatar_url' => route('web.user.update.avatar', $user->hash_id)
        ]);

        $title = __('dcm.myprofile_label') . ' - '. dcmConfig('site_name');
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
        return view('web.user.profile', $data)->with($data);
    }


    /**
     * Update User Profile.
     *
     * @param UpdateRequest $request
     * @return Illuminate\Support\Facades\View
     */
    public function postUpdateProfile(UpdateRequest $request)
    {

        try {

            $credentials = array_only($request->all(), [
                'first_name',
                'last_name',
                'email',
                'username',
                'password',
                'new_password',

            ]);


            $userId  = $this->auth->id();

            if( $userId < 1)
                throw new Exception(__('user::module.not_allowed'));

            if( !$request->get('profile_type') ) {
                $credentials = array_merge($credentials,  array_only($request->all(), [
                    'email',
                    'username'
                ]));
            }
            $credentials  = array_filter($credentials);

            if (  isset($credentials['password']) )
                $this->auth->validateCredentials($this->auth->user(), $credentials);

            // cannot update if demo mode on is true.
            if ( env('DEMO_MODE_ON', false) === false )
                $user = $this->auth->update($userId, $credentials);

            return redirect()->back()
                        ->withSuccess(__('user::module.success_update'));

        } catch (Exception $e) {

            if(isAjax())
                return $this->failed($e->getMessage());

            return $this->redirectWithError($e->getMessage());
        }
    }


    /**
     * Undocumented function
     *
     * @param UpdateAvatarRequest $request
     * @param [type] $userHashId
     * @return json
     */
    public function postUpdateAvatar(Request $request, $userHashId = null)
    {
        try {
            if ( !$userHashId )
                throw new Exception(__('user::module.no_hash_id'));

            $user    = $this->auth->user();

            if ( !$user->compareHashId( $userHashId ) )
                throw new Exception(__('user::module.user_id_not_match'));

            $uploadModel = $this->userModel
                            ->setLoggedInUser($user)
                            ->updateProfileAvatar( $request->all() );

            return $this->success([
                'message' => __('user::module.success_avatar_changed'),
                'avatar_url' => $uploadModel->avatar_url
            ]);

        } catch (Exception $e) {
            return $this->failed($e->getMessage());
        }
    }

    private function redirectWithError( $message = null) {
        return redirect()->back()->withInput()->withError(['message' => $message]);
    }

}
