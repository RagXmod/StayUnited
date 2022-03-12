<?php

/**
 * Module User: Modules\User\Http\Requests\NewAccountRequest
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

class NewAccountRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        $userName = $this->offsetGet('username');
        $email    = $this->offsetGet('email');

        return [
            'first_name' => 'required',
            'last_name'  => 'required',
            'username'   => 'required|min:3|unique:users,Username,'.$userName ?? null,
            'email'      => 'required|unique:users,Email,'.$email ?? null,
            'password'   => 'required',
        ];
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
