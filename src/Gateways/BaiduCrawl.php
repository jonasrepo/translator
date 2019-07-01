<?php
/**
 * BaiduCrawl.php
 *
 * Author: Guo
 *
 * Date:   2019-06-28 13:14
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Jonas\Translator\Gateways;
use Jonas\Translator\Exceptions\GatewayErrorException;
use Jonas\Translator\Exceptions\HttpErrorException;
use Jonas\Translator\Lng;
use Jonas\Translator\Result;

/**
 * Class BaiduCrawl
 *
 * @package Jonas\Translator\Gateways
 */
class BaiduCrawl extends AbsGateway
{
    /**
     * url endpoint
     */
    const ENDPOINT             = 'https://fanyi.baidu.com/v2transapi';
    /**
     * detect lang endpoint
     */
    const LANG_DETECT_ENDPOINT = 'https://fanyi.baidu.com/langdetect';
    /**
     * gtk
     */
    const GTK   = '320305.131321201';
    /**
     * token
     */
    const TOKEN = 'eabe3f689d585c828f6192caa9622f32';

    /**
     * @var bool
     */
    protected $support_to_lng_auto = false;

    /**
     * @param $str
     *
     * @return Result
     * @throws GatewayErrorException
     * @throws HttpErrorException
     */
    public function translate($str)
    {
        $lan = $this->getInputLang($str);
        $res = $this->post(self::ENDPOINT, [
            "from" => $lan,
            "to" => $this->autoChooseToLag($str),
            "query" => $str,
            "transtype" => "realtime",
            "simple_means_flag" => "3",
            "sign" => $this->generateSign($str, self::GTK),
            "token" => self::TOKEN,
        ], [
            'referer' => 'https://fanyi.baidu.com',
            'cookie' => ' BAIDUID=C719A0724B2D6C84055F17177C32E077:FG=1;',//$this->getReceivedCookieStr(),
        ]);
        if(isset($res['error'])) {
            throw new GatewayErrorException($res['error'], $res['error'], $res);
        }
        $dict_rest = isset($res['dict_result']['simple_means']['word_means']) ? $res['dict_result']['simple_means']['word_means'] : [];
        return new Result($res['trans_result']['data'][0]['dst'], $dict_rest, $res);
    }

    /**
     * @param $r
     * @param $_gtk
     *
     * @return float|int|mixed
     */
    protected function generateSign($r , $_gtk)
    {
        $o = mb_strlen($r);
        if($o > 30){
            $r = mb_substr($r, 0, 10) . mb_substr($r, intval($o/2) - 5, 10) . mb_substr($r, -10, 10);
        }  
        $t = $C = $_gtk;
        $e = explode('.', $t);
        $h = $e[0];
        $i = $e[1];
        $d = [];
        $f = $g =0;
        for($e ; $g < mb_strlen($r); $g++){
           $m = char_code_at($r, $g);
           if(128 > $m) {
            $d[$f++] = $m;
           }else {
                if(2048 > $m) {
                    $d[$f++] = $m >> 6 | 192;
                }else {
                    if((55296 === (64512 & $m)) && ($g + 1 < $o) && (56320 === (64512 & char_code_at($o, $g+1)))) {
                        $m = 65536 + ((1023 & $m) << 10) + (1023 & char_code_at($o, ++$g));
                        $d[$f++] = $m >> 18 | 240;
                        $d[$f++] = $m >> 12 & 63 | 128;
                    }else {
                        $d[$f++] = $m >> 12 | 224;
                        $d[$f++] = $m >> 6 & 63 | 128;
                        $d[$f++] = 63 & $m | 128;
                    }
                }
           }
        }

        for ($S = $h, $u = "+-a^+6", $l = "+-3^+b+-f", $s = 0; $s < count($d); $s++) {
            $S += $d[$s]; $S = $this->a($S, $u);
        }

        $S = $this->a($S, $l);
        $S ^= $i;
        if(0 > $S) {
            ($S = (2147483647 & $S) + 2147483648);
        }
        $S %= 1e6;
        $S .= "." . ($S ^ $h);
        return $S;
    }

    /**
     * @param $r
     * @param $o
     *
     * @return int
     */
    protected function a($r, $o) {
        for ($t = 0; $t < mb_strlen($o) - 2; $t += 3) {
            $a = mb_substr($o, $t + 2, 1);
            if($a >= "a" ) {
                $a = char_code_at($a,0) - 87;
            }else {
                $a = (int) $a;
            }
            if( "+" === mb_substr($o,$t + 1, 1)) {
                $a =  $r >> $a ;
            }else {
                $a =  $r << $a ;
            }

            if("+" === mb_substr($o, $t, 1) ){
                $r  =  $r + $a & 4294967295 ;
            }else {
                $r = $r ^ $a;
            }
        }
        return $r;
    }

    /**
     *
     * @param $str
     *
     * @return string
     * @throws HttpErrorException
     */
    protected function getInputLang($str)
    {
        try {
            $res = $this->post(self::LANG_DETECT_ENDPOINT, [
                'query' => $str,
            ], [
                'authority'=> 'fanyi.baidu.com',
                'referer'=> 'https://fanyi.baidu.com/',
            ]);
            if(isset($res['error']) && $res['error'] === 0 ) {
                return $res['lan'];
            }
            return 'en';
        } catch (\Exception $e) {
            throw new HttpErrorException($e->getMessage(), -1, [
                'endpoint' => self::LANG_DETECT_ENDPOINT,
            ]);
        }
    }
}

