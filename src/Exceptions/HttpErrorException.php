<?php
/**
 * HttpErrorException.php
 *
 * Author: Guo
 *
 * Date:   2019-06-29 09:10
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Jonas\Translator\Exceptions;


use Throwable;

class HttpErrorException extends \Exception
{
    public $raw = [];
    public function __construct($message = "", $code = 0, array $raw = [])
    {
        parent::__construct($message, $code);
        $this->raw = $raw;
    }
}