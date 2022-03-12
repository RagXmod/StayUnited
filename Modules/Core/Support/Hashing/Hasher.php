<?php

namespace Modules\Core\Support\Hashing;

/**
 * 
 * 
 * @package    DCM
 * @author     Anthony Pillos <dev.anthonypillos@gmail.com>
 * @copyright  2018 (c) DCM
 * @version    Release: v1.0.0
 * @link       http://devcorpmanila.com
 */

use Hashids\Hashids;

class Hasher
{
    public static function encode(...$args)
    {
        return app(Hashids::class)->encode(...$args);
    }

    public static function decode($enc)
    {
        if (is_int($enc)) {
            return $enc;
        }
        return app(Hashids::class)->decode($enc)[0];
    }
}