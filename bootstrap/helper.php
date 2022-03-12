<?php


if (!function_exists('formattedFileSize')) {
    function formattedFileSize($bytes, $si = false)
    {
        $thresh = 1024;
        if ($si) $thresh = 1000;
        $units = array('B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB');
        if ($si)
            $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
        $power = $bytes > 0 ? floor(log($bytes, $thresh)) : 0;
        return round($bytes / pow($thresh, $power), 1) . $units[$power];
    }
}

/**
* countFormat()
**/
if(!function_exists('countFormat')){
        function countFormat($num) {
            $x = round($num);
            $x_number_format = number_format($x);
            $x_array = explode(',', $x_number_format);
            $x_parts = array('K', 'M', 'B', 'T');
            $x_count_parts = count($x_array) - 1;
            $x_display = $x;
            $x_display = @$x_array[0] . ((int) @$x_array[1][0] !== 0 ? '.' . @$x_array[1][0] : '');
            $x_display .= @$x_parts[@$x_count_parts - 1];
            return $x_display;
        }
}


if(!function_exists('parse_size')){
    function parse_size($size) {
        $unit = preg_replace('/[^bkmgtpezy]/i', '', $size); // Remove the non-unit characters from the size.
        $size = preg_replace('/[^0-9\.]/', '', $size); // Remove the non-numeric characters from the size.
        if ($unit) {
            // Find the position of the unit in the ordered string which is the power of magnitude to multiply a kilobyte by.
            return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
        }
        else {
            return round($size);
        }
    }
}


if(!function_exists('fileUploadMaxSizeLimit')){
    function fileUploadMaxSizeLimit() {

        return cache()->rememberForever('upload_max_size_limit', function () {
            static $max_size = -1;

            if ($max_size < 0) {
                // Start with post_max_size.
                $post_max_size = parse_size(ini_get('post_max_size'));
                if ($post_max_size > 0) {
                    $max_size = $post_max_size;
                }

                // If upload_max_size is less, then reduce. Except if upload_max_size is
                // zero, which indicates no limit.
                $upload_max = parse_size(ini_get('upload_max_filesize'));
                if ($upload_max > 0 && $upload_max < $max_size) {
                    $max_size = $upload_max;
                }
            }
            return formattedFileSize($max_size);
        });

    }
}
if(!function_exists('paginateCollection')){
    function paginateCollection($items, $perPage = 15, $page = null, $options = [])
    {
        //  dd($paginatedItems);
        $page = $page ?: (\Illuminate\Pagination\Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof \Illuminate\Support\Collection ? $items : \Illuminate\Support\Collection::make($items);
        $paginatedItems =  new \Illuminate\Pagination\LengthAwarePaginator(array_values($items->forPage($page, $perPage)->toArray()), $items->count(), $perPage, $page, $options);
        $paginatedItems->setPath(request()->url());

        return $paginatedItems;

    }
}


if(!function_exists('showAds')){
    function showAds($identifier)
    {
        $model = app(\App\App\Eloquent\Repositories\HomeAdsPlacementBlockRepositoryEloquent::class);
        
        $adCollections = $model->showAds();
        if (isset($adCollections[$identifier]))
            return $adCollections[$identifier];
        return '';
    }
}