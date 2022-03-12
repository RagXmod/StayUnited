<?php

if(!function_exists('querylog')){

	function querylog(Closure $callback){
		DB::enableQueryLog();
		$callback();
		$query = DB::getQueryLog();
		foreach($query as $q) {
			$queryBind = vsprintf(str_replace('?', "'%s'", $q['query']), $q['bindings']);
			pre($queryBind);
		}
		pre($query);
		return true;
	}
}


/**
* pre()
* short hand for printing array/string data
*
* @return array/string
**/
if(!function_exists('pre')){
	function pre($str) {
		echo '<pre/>';
		return print_r($str);
	}
}

if(!function_exists('hasRoute')){
	function hasRoute($routeName) {
		return Route::has($routeName);
	}
}

if(!function_exists('blankBase64Image')){
	function blankBase64Image() {
		return 'data:image/png;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=';
	}
}

if(!function_exists('isAjax')){
	function isAjax() {
		return request()->wantsJson() || request()->ajax();
	}
}

if ( !function_exists('hasher') )
{
	function hasher($data, $decode = false) {
		$hash = app(\Modules\Core\Support\Hashing\Hasher::class);
		try {
			if( $decode === true)
				return $hash->decode($data);
			return $hash->encode($data);
		} catch (Exception $e) {
			return null;
		}
	}
}


if (!function_exists('fixedJSON'))
{
	function fixedJSON($str) {

		if ( is_array($str) === true || is_object($str) === true )
			$str = json_encode($str);

		$from = array('"');    // Array of values to replace
		$to = array('\\"');    // Array of values to replace with

		// Replace the string passed
		return str_replace( $from, $to, $str );
	}
}



// columnSort($getInfo, array('image_name', 'asc'));
$globalMultisortVar = array();
if(!function_exists('columnSort')){
    function columnSort(&$recs, $cols) {
        global $globalMultisortVar;
        $globalMultisortVar = $cols;
        usort($recs, 'multiStrnatcmp');
        return($recs);
    }
}

if(!function_exists('multiStrnatcmp')){
    function multiStrnatcmp($a, $b) {
        global $globalMultisortVar;
        $cols = $globalMultisortVar;
        $i = 0;
        $result = 0;
        while ($result == 0 && $i < count( (array) $cols)) {
            $result = ($cols[$i + 1] == 'desc' ? strnatcmp($b[$cols[$i]], $a[$cols[$i]]) : $result = strnatcmp($a[$cols[$i]], $b[$cols[$i]]));
            $i+=2;
        }
        return $result;
    }
}



if ( !function_exists('jTraceEx') ) {
	/**
	* jTraceEx() - provide a Java style exception trace
	* @param $exception
	* @param $seen      - array passed to recursive calls to accumulate trace lines already seen
	*                     leave as NULL when calling this function
	* @return array of strings, one entry per trace line
	*/
	function jTraceEx($e, $seen=null) {
		$starter = $seen ? 'Caused by: ' : '';
		$result = array();
		if (!$seen) $seen = array();
		$trace  = $e->getTrace();
		$prev   = $e->getPrevious();
		$result[] = sprintf('%s%s: %s', $starter, get_class($e), $e->getMessage());
		$file = $e->getFile();
		$line = $e->getLine();
		while (true) {
				$current = "$file:$line";
				if (is_array($seen) && in_array($current, $seen)) {
						$result[] = sprintf(' ... %d more', count($trace)+1);
						break;
				}
				$result[] = sprintf(' at %s%s%s(%s%s%s)',
																		count($trace) && array_key_exists('class', $trace[0]) ? str_replace('\\', '.', $trace[0]['class']) : '',
																		count($trace) && array_key_exists('class', $trace[0]) && array_key_exists('function', $trace[0]) ? '.' : '',
																		count($trace) && array_key_exists('function', $trace[0]) ? str_replace('\\', '.', $trace[0]['function']) : '(main)',
																		$line === null ? $file : basename($file),
																		$line === null ? '' : ':',
																		$line === null ? '' : $line);
				if (is_array($seen))
						$seen[] = "$file:$line";
				if (!count($trace))
						break;
				$file = array_key_exists('file', $trace[0]) ? $trace[0]['file'] : 'Unknown Source';
				$line = array_key_exists('file', $trace[0]) && array_key_exists('line', $trace[0]) && $trace[0]['line'] ? $trace[0]['line'] : null;
				array_shift($trace);
		}
		$result = join("\n", $result);
		if ($prev)
				$result  .= "\n" . jTraceEx($prev, $seen);

		return $result;

	}
}
