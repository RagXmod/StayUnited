<?php

/**
* arrayKeywordsToCommaString()
*
* @return string
**/
if (!function_exists('arrayKeywordsToCommaString')) {
    function arrayKeywordsToCommaString($data)
    {
        if($data == '')
                return '';

        $keywordArray = [];
        foreach ($data as $key => $val) {
                $keywordArray[] = str_slug($val);
        }
        return implode(',', $keywordArray);
    }
}


/**
* commaStringToArrayKeywords()
*
* @return string
**/
if (!function_exists('commaStringToArrayKeywords')) {
    function commaStringToArrayKeywords($data)
    {
        $keyArr = explode(',', $data);
        $keywordArray = [];
        foreach ($keyArr as $key => $word) {
                // $keywordArray[] = ['text' => $word];
                $keywordArray[] = $word;
        }
        return $keywordArray;
    }
}

/**
* pageStatusArr()
*
* @return string
**/
if (!function_exists('pageStatusArr')) {
    function pageStatusArr( $identifier = '', $forSelectOption = false)
    {
        $collections = Cache::rememberForever(config('page.page_status_cache_key'), function () {
            $items       = config('page.status');
            $collections = [];
            foreach( $items as $item) {
                switch ($item) {
                    case config('page.status.published'):
                        $collections[$item]  = __('page::page.status.published');
                        break;
                    case config('page.status.pending'):
                        $collections[$item]  = __('page::page.status.pending');
                        break;
                    case config('page.status.is_draft'):
                        $collections[$item]  = __('page::page.status.is_draft');
                        break;
                }
            }
            return $collections;
        });

        if ( $identifier)
            return isset($collections[$identifier]) ? $collections[$identifier] : config('page.status.published');

        if ( $forSelectOption === true) {

            $_collections = [];
            foreach( $collections as $index => $item) {
                $_collections[] = [
                    'identifier' => $index,
                    'title'      => $item,
                    'selected'   => ( $index === config('page.status.published') ) ? true : false
                ];
            }
            return $_collections;
        }
        return $collections;
    }
}