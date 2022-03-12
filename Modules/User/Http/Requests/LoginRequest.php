<?php

/**
 * Module User: Modules\User\Http\Requests\LoginRequest
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
use Modules\User\Rules\ReCaptcha;

class LoginRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $userRules = [
            'login'    => 'required',
            'password' => 'required',
        ];

        if( dcmConfig('is_recaptcha') == 'yes' && dcmConfig('recaptcha_on_login') == 'yes' )
            $userRules['g-recaptcha-response'] = ['required', new ReCaptcha];

        return $userRules;
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
