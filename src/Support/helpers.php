<?php
/**
 * helpers.php
 *
 * Author: Guo
 *
 * Date:   2019-06-28 17:41
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

if(!function_exists('char_code_at')) {
    /**
     * the function act the same as javascript string.charCodeAt
     */
    function char_code_at($str, $index){
        $char = mb_substr($str, $index, 1, 'UTF-8');
        if (mb_check_encoding($char, 'UTF-8'))
        {
            $ret = mb_convert_encoding($char, 'UTF-32BE', 'UTF-8');
            return hexdec(bin2hex($ret));
        } else {
            return null;
        }
    }
}

if(!function_exists('is_mb_string')) {
    /**
     * determin if the string is multi byte string
     * @param $string
     *
     * @return bool
     */
    function is_mb_string($string) {
        return mb_strlen($string,'utf-8') !== strlen($string);
    }
}
