<?php
/**
 * Result.php
 *
 * Author: Guo
 * Email jonasyeah@163.com
 *
 * Date:   2019-07-01 09:28
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Jonas\Translator;


class Result
{
    protected $trans_result;
    protected $dict_result;
    protected $raw;

    /**
     * Result constructor.
     *
     * @param $trans_result
     * @param array $dict_result
     * @param array $raw_result
     */
    public function __construct($trans_result, $dict_result, $raw_result)
    {
        $this->trans_result = $trans_result;
        $this->dict_result  = $dict_result;
        $this->raw          = $raw_result;
    }

    /**
     * @return mixed
     */
    public function getTransResult()
    {
        return $this->trans_result;
    }


    /**
     * @return mixed
     */
    public function getDictResult()
    {
        return $this->dict_result;
    }


    /**
     * @return mixed
     */
    public function getRaw()
    {
        return $this->raw;
    }

    public function __toString()
    {
        return $this->trans_result;
    }
}