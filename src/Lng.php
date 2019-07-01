<?php
/**
 * Lng.php
 *
 * Author: Guo
 *
 * Date:   2019-06-29 13:53
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Jonas\Translator;


use Jonas\Translator\Exceptions\InvalidArgumentException;

/**
 * Class Lng
 *
 * @package Jonas\Translator
 */
final class Lng
{
    /**
     * 中文
     */
    const CHINESE    = 'zh-CHS';

    /**
     *英文
     */
    const ENGLISH    = 'en';
    /**
     *日文
     */
    const JAPANESE   = 'ja';
    /**
     *法语
     */
    const FRENCH     = 'fr';

    /**
     *西班牙语
     */
    const ESPANOL    = 'es';
    /**
     *葡萄牙语
     */
    const PORTUGUESE = 'pt';
    /**
     *意大利语
     */
    const ITALIAN    = 'it';
    /**
     *俄文
     */
    const RUSSIAN    = 'ru';
    /**
     *越南文
     */
    const VIETNAMESE = 'vi';
    /**
     *德文
     */
    const GERMAN     = 'de';
    /**
     *阿拉伯文
     */
    const ARABIC     = 'ar';

    /**
     * check if is valid
     * @param $lng
     *
     * @return bool
     */
    public function isValid($lng)
    {
        return in_array($lng, [
            'zh-CHS', 'en', 'ja', 'fr', 'es', 'pt', 'it', 'ru', 'vi', 'de', 'ar'
        ]);
    }


    /**
     *lng mapping
     * @param $lng
     *
     * @return mixed
     * @throws InvalidArgumentException
     */
    public static function convertToBaidu($lng)
    {
        $baidu_mapper = [
            'zh-CHS' => 'zh',
            'en' => 'en',
            'ja' => 'jp',
            'fr' => 'fra',
            'es' => 'spa',
            'pt' => 'pt',
            'it' =>'it',
            'ru' => 'ru',
            'vi'=>'vie',
            'de'=>'de',
            'ar'=>'ara',
        ];

        if(!in_array($lng, array_keys($baidu_mapper))) {
           throw new InvalidArgumentException("暂不支持改语言 {$lng}");
        }
        return $baidu_mapper[$lng];
    }

    /**
     * @param $lng
     *
     * @return mixed
     */
    public static function convertToYoudao($lng)
    {
        return $lng;
    }
}