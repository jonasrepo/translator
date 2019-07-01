<?php
/**
 * TranslateManager.php
 *
 * Author: Guo
 *
 * Date:   2019-06-27 17:13
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Jonas\Translator;


/**
 * Class TranslateManager
 *
 * @package Jonas\Translator
 */
class TranslateManager
{
    /**
     * success
     */
    const STATUS_SUCCESS = 'success';

    /**
     * failure
     */
    const STATUS_FAILURE = 'failure';
    /**
     * @var Translator
     */
    protected $translator;

    /**
     * TranslateManager constructor.
     *
     * @param Translator $translator
     */
    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param       $str
     * @param array $gateways
     *
     * @return array|mixed
     */
    public function translate($str, array $gateways = [])
    {
        $results = [];
        foreach ($gateways as $gateway) {
            try{
                $res = $this->translator->gateway($gateway)
                    ->to($this->translator->getTo($gateway))
                    ->translate($str);
                $results[$gateway] = [
                    'gateway' => $gateway,
                    'status' => self::STATUS_SUCCESS,
                    'result' => $res,
                ];

            }catch (\Exception $e){
                $results[$gateway] = [
                    'gateway' => $gateway,
                    'status' => self::STATUS_FAILURE,
                    'exception' => $e,
                ];
            }
        }

        if(count($results) === 1) {
            return current($results);
        }

        return $results;
    }
}