<?php

if(!function_exists('defaultProfileAvatar')){
	function defaultProfileAvatar() {
		return asset('assets/img/profile.png');
	}
}


/**
* userStatusArr()
*
* @return string
**/
if (!function_exists('userStatusArr')) {
    function userStatusArr( $identifier = '', $forSelectOption = false)
    {
        $collections = Cache::rememberForever(config('user.user_status_cache_key'), function () {
            $items       = config('user.status');
            $collections = [];
            foreach( $items as $item) {
                switch ($item) {
                    case config('user.status.active'):
                        $collections[$item]  = __('user::user.status.active');
                        break;
                    case config('user.status.banned'):
                        $collections[$item]  = __('user::user.status.banned');
                        break;
                    case config('user.status.suspend'):
                        $collections[$item]  = __('user::user.status.suspend');
                        break;
                    case config('user.status.unconfirmed'):
                        $collections[$item]  = __('user::user.status.unconfirmed');
                        break;
                }
            }
            return $collections;
        });

        if ( $identifier)
            return isset($collections[$identifier]) ? $collections[$identifier] : config('user.status.active');

        if ( $forSelectOption === true) {

            $_collections = [];
            foreach( $collections as $index => $item) {
                $_collections[] = [
                    'identifier' => $index,
                    'title'      => $item,
                    'selected'   => ( $index === config('user.status.active') ) ? true : false
                ];
            }
            return $_collections;
        }
        return $collections;
    }
}