<?php

namespace Modules\User\Rules;

use Illuminate\Contracts\Validation\Rule;

use ReCaptcha\ReCaptcha as GoogelReCaptcha;

class ReCaptcha implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {

        if ( dcmConfig('is_recaptcha') === 'yes' ) {
            $captcha = new GoogelReCaptcha( dcmConfig('recaptcha_site_secret_key') );
            $response = $captcha->verify($value, $_SERVER['REMOTE_ADDR']);
            return $response->isSuccess();
        }
        return false;

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Complete the reCAPTCHA to submit the form';
    }
}
