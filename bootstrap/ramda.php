<?php

namespace Phamda {
    function _() {
        static $placeholder;
        if ($placeholder === null) {
            $placeholder = new \stdClass;
        }
        return $placeholder;
    }

    function curryN($n, $f) {
        $curryNRec = function($recv) use ($n, $f, &$curryNRec) {
            return function () use ($recv, $n, $f, &$curryNRec) {
                $left = $n;
                $argsIdx = 0;
                $combined = array();
                $combindedIdx = 0;
                $args = func_get_args();
                while ($combindedIdx < count($recv) || $argsIdx < count($args)) {
                    if ($combindedIdx < count($recv)
                        && ($recv[$combindedIdx] !== _() || $argsIdx > count($args))) {
                        $result = $recv[$combindedIdx];
                    } else {
                        $result = $args[$argsIdx];
                        $argsIdx += 1;
                    }
                    $combined[$combindedIdx] = $result;
                    $combindedIdx += 1;
                    if ($result !== _()) {
                        $left -= 1;
                    }
                }
                if ($left <= 0) {
                    return call_user_func_array($f, $combined);
                } else {
                    return $curryNRec($combined);
                }
            };
        };
        return $curryNRec([]);
    }

    function curry($f) {
        $fRefl = new \ReflectionFunction($f);
        return curryN($fRefl->getNumberOfParameters(), $f);
    }
}