<?php

namespace Modules\User\Http\Controllers;

use Exception;
use DataTables;
use Illuminate\Http\Request;
use Modules\Core\Traits\ResponseTrait;
use Modules\Core\Http\Controllers\BaseController;
use Modules\User\Eloquent\Repositories\UserRepositoryEloquent;
use DB;

class UserController extends BaseController
{

    use ResponseTrait;

    public $routes = [
        'edit_page' =>  null
    ];

    public $userModel;

    public function __construct(UserRepositoryEloquent $userModel)
    {
        parent::__construct();
        $this->userModel = $userModel;
    }


    public function setRoutes( $route) {
        $this->routes  = array_merge($this->routes,$route);
        return $this;
    }


    public function detail( $id ) {
        $model = $this->userModel->with(['roles','userDetail'])->find($id);
        // dd($model->toArray());
        return $model;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        try {

            $model = $this->userModel->makeModel()->query();
            return DataTables::eloquent($model)
                            ->order(function ($query) {
                                $query->orderBy('created_at', 'desc');
                            })
                            ->editColumn('created_at', function ($item) {
                                return $item->created_at->diffForHumans();
                            })
                            ->addColumn('action', function ($item) {
                                $editLink  = route($this->routes['edit_page'], $item->id);
                                return "
                                    <a href='$editLink' class='btn btn-sm btn-success'>
                                        <i class='fas fa-pen'></i> Edit
                                    </a>
                                    <button class='btn btn-sm btn-dark del_btn' data-pageid='$item->id'>
                                        <i class='fas fa-trash'></i> Delete
                                    </button>
                                ";
                            })
                            ->setRowId(function ($item) {
                                return $item->id;
                            })
                            ->setRowAttr([
                                'title' => function($item) {
                                    return str_limit(e($item->full_name), 30);
                                },
                            ])
                            ->escapeColumns(['title'])
                            ->toJson();

        } catch (Exception $e) {
            logger()->error($e);
            return $this->failed($e->getMessage());
        }
    }


    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {

        $request->validate([
            'first_name' => 'required',
            'email'      => 'required',
            'username'   => 'required'
        ]);

        try {

            DB::beginTransaction();

            $input = $request->all();
            $credentials = $request->only(['email', 'first_name','last_name','username','password']);

            // add condition here.. for auto activate account or not
            if ( env('DEMO_MODE_ON', false) !== false )
                throw new Exception('System is in demo mode, permission not granted.');

            $userModel     = $this->auth->registerAndActivate($credentials);

            if (  isset($input['user_detail']) )
                $userModel->userDetail()->updateOrCreate(['user_id' => $userModel->id], $input['user_detail'] );

            if (  isset($input['roles']) ) {
                $roles  = $input['roles'];

                // attach new roles
                $this->attachedRolesToUser($roles, $userModel);

            } else {
                $regularRole = $this->auth->findRoleBySlug('regular');
                if ( $regularRole )
                    $this->auth->assignRole($userModel, $regularRole);
            }

            // process user status, active, banned, suspend etc..
            if (  isset($input['status_identifier']) && $input['status_identifier'] != config('user.status.active'))
                $this->userModel->statusAction($userModel,  $input['status_identifier'] );


            DB::commit();


            $modelArray = $userModel->toArray();
            $modelArray['dcm_detail_url'] = route($this->routes['edit_page'], $userModel->id);
            return $this->success($modelArray);


        } catch (Exception $e) {
            logger()->error($e);
            DB::rollback();
            return $this->failed($e->getMessage());
        }
    }


    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request, $id)
    {

        $request->validate([
            'first_name' => 'required',
            'email'      => 'required',
            'username'   => 'required'
        ]);


        try {

            DB::beginTransaction();

            $model = $this->userModel->find($id);
            $input = $request->all();

            if(!$model)
                throw new Exception("Failed to find user by id.");


            $credentials = array_only($input, [
                    'first_name',
                    'last_name',
                    'email',
                    'username'

            ]);

            $hasExistingUserModel = $this->userModel->makeModel()
                                    ->where('username', $credentials['username'])
                                    ->orWhere('email',$credentials['email'])
                                    ->first();

            if ($hasExistingUserModel)
                if ($hasExistingUserModel->id != $model->id)
                    throw new Exception("Email address / username already exist.");

            if (  isset($input['password']) ) {
                $_credentials = array_only($input, ['email', 'password']);
                // $isValidCredentials = $this->auth->validateCredentials($model, $_credentials);
                // if ( !$isValidCredentials)
                //     throw new Exception('Email / Password does not match, please try again.');
                $this->auth->update($model->id, $_credentials);
            }

            if (  isset($input['roles']) ) {
                $roles  = $input['roles'];

                // detached current roles
                $this->attachedRolesToUser($roles, $model, true);

                // attach new roles
                $this->attachedRolesToUser($roles, $model);

            }

            if (  isset($input['user_detail']) )
                $model->userDetail->fill( $input['user_detail'] )->save();

            // process user status, active, banned, suspend etc..
            if (  isset($input['status_identifier']) )
                $this->userModel->statusAction($model,  $input['status_identifier'] );

            if ( env('DEMO_MODE_ON', false) === false )
                $model->fill( $credentials)->save();

            DB::commit();
            return $this->success($model);

        } catch (Exception $e) {
            logger()->error($e);
            DB::rollback();
            return $this->failed($e->getMessage());
        }

    }

    /**
     * Remove the specified resource from storage.
     * @return Response
    */
    public function destroy($id)
    {
        try {

            $modelObj = $this->userModel->find($id);
            if($modelObj)
            {
                $dataObj = $modelObj;
                if ( env('DEMO_MODE_ON', false) !== false )
                    return $this->success( sprintf('Successfully deleted user (%s).',$dataObj->full_name));

                $destroyed = $modelObj->delete();
                if($destroyed)
                    return $this->success( sprintf('Successfully deleted user (%s).',$dataObj->full_name));
            }
            return $this->failed('Failed to delete user.');

        } catch (Exception $e) {
            logger()->error($e);
            return $this->failed($e->getMessage());
        }
    }


    public function statusCollections() {
        return userStatusArr(null, true);
    }

    public function getAllRoles() {
        return $this->userModel->getAllRoles();
    }


    /**
     * Undocumented function
     *
     * @param [type] $roles
     * @param [type] $userModel
     * @return void
     */
    public function attachedRolesToUser($roles, $userModel, $detach = false) {

        if ($detach === false) {
            // attach new roles
            $selectedRoles = array_pluck($roles,'id');
            $userModel->roles()->attach($selectedRoles);
        } else {

            $userRoles = $userModel->roles->pluck('id')->toArray();
            $userModel->roles()->detach($userRoles);
        }
    }
}
