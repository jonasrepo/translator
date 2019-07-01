<?php
/**
 * GatewayInterface.php
 *
 * Author: Guo
 *
 * Date:   2019-06-27 16:56
* This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Jonas\Translator\Contracts;


interface GatewayInterface
{
    function translate($string);
}