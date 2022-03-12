<?php


namespace Modules\Core\Traits;

/**
 * Module Api: Modules\Core\Traits\SanitizedRequestTrait
 *
 * Sanitize Input Requests
 *
 * @package    DCM
 * @author     Anthony Pillos <dev.anthonypillos@gmail.com>
 * @copyright  2018 (c) DCM
 * @version    Release: v1.0.0
 * @link       http://devcorpmanila.com
 */
// using realtime-facade
// use Facades, in front for real-time facade
use Facades\Modules\Core\Support\Input\Sanitizer;

trait SanitizedRequestTrait {

    /**
     * Sanitize request data before validation.
     */
    protected function prepareForValidation()
    {
        $this->sanitize($this->sanitizers());
    }

    /**
     * Sanitize request data after validation will pass.
     */
    protected function sanitizeAfterValidationPass()
    {
        $this->sanitize($this->afterSanitizers());
    }

    /**
     * Sanitize input data.
     */
    protected function sanitize(array $sanitizers)
    {
        $inputs = $this->all();

        // it was applie already in middleware..
        $inputs =  Sanitizer::skipFromXSSClean()->sanitize($sanitizers, $inputs);
        $this->replace($inputs);
    }

    /**
     * Return list of validation rules.
     *
     * @return array
     */
    public function rules()
    {
        if (property_exists($this, 'rules')) {
            return $this->rules;
        }

        return [];
    }

    /**
     * Return the sanitizers to be applied to the data.
     *
     * @return array
     */
    protected function sanitizers()
    {
        if (property_exists($this, 'sanitizers')) {
            return $this->sanitizers;
        }

        return [];
    }

    /**
     * Return the sanitizers to be applied to the data after validation will pass.
     *
     * @return array
     */
    protected function afterSanitizers()
    {
        if (property_exists($this, 'afterSanitizers')) {
            return $this->afterSanitizers;
        }

        return [];
    }
}