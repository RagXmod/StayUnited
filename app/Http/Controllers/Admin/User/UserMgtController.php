<?php

/**
 * Module Core: App\Http\Controllers\Admin\User\UserMgtController
 *
 * Long description for class (if any)...
 *
 * @package    DCM
 * @author     Anthony Pillos <dev.anthonypillos@gmail.com>
 * @copyright  2019 (c) DCM
 * @version    Release: v1.0.0
 * @link       http://devcorpmanila.com
 */

namespace App\Http\Controllers\Admin\User;

use Exception;
use Modules\User\Eloquent\Repositories\UserRepositoryEloquent;
use Modules\User\Http\Controllers\UserController as Controller;

class UserMgtController extends Controller
{

    public function __construct(UserRepositoryEloquent $userModel)
    {
        $this->setRoutes( [
            'edit_page' => 'admin.user.detail'
        ]);
        parent::__construct( $userModel );
    }


    public function getIndex()
    {
        $data = [];
        return view('admin.usermgt.index', $data);
    }

    public function getDetail($id)
    {
        $item = $this->detail($id);
        if( !$item ) abort(404);


        if ( $item->userDetail ) {
            $userDetail = $item->userDetail->only(['about_me']);
            $item = array_merge($item->toArray() , $userDetail);
        }

        $statusCollections = $this->statusCollections();
        $getAllRoles = $this->getAllRoles();
        return view('admin.usermgt.detail', compact('item', 'statusCollections','getAllRoles'));
    }

    public function getCreate()
    {
        $item = [
            'page_type' => 'create',
            'pageindex' => route('admin.user.index')
        ];
        $statusCollections = $this->statusCollections();
        $getAllRoles = $this->getAllRoles();
        return view('admin.usermgt.detail', compact('item','statusCollections', 'getAllRoles'));
    }
}
