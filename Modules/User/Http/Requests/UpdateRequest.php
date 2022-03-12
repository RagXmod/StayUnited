<?php

/**
 * Module User: Modules\User\Http\Requests\UpdateRequest
 *
 * Long description for class (if any)...
 *
 * @package    DCM
 * @author     Anthony Pillos <dev.anthonypillos@gmail.com>
 * @copyright  2018 (c) DCM
 * @version    Release: v1.0.0
 * @link       http://devcorpmanila.com
 */

namespace Modules\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{

    private $userTokenIdInput = 'fexiephlien7_user_token_id';
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        $rules = [];
        $hasProfileType = $this->offsetGet('profile_type');
        if( $hasProfileType && $hasProfileType == 'user-informations')
            $rules = [
                'first_name'  => 'min:2',
                'last_name'   => 'min:2',
            ];
        else {
            // this rules is for account details.
            $rules = [
                'email'    => 'required|email',
                'username' => 'required|min:2'
            ];
            $oldPassword = $this->offsetGet('old_password');
            if( $oldPassword  ) {
                $rules['password']     = 'min:3|confirmed';
                $rules['old_password'] = 'min:3|required_if:password, !=, null';
            }
        }
        return $rules;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function messages()
    {
        return [];
    }
}
