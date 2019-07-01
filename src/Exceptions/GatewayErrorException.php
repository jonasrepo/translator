<?php
/**
 * GatewayErrorException.php
 *
 * Author: Guo
 *
 * Date:   2019-06-28 14:39
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Jonas\Translator\Exceptions;


use Throwable;

class GatewayErrorException extends \Exception
{
    public $raw = [];
    public function __construct($message = "", $code = 0, $raw = [])
    {
        parent::__construct($message, intval($code));
        $this->raw = $raw;
    }

}