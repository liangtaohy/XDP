<?php
/**
 * 小程序基础工具类
 *
 * User: Lotushy (liangtaohy@gmail.com)
 * Date: 2018/10/24
 * Time: 下午5:27
 */

namespace Xdp\Miniprogram;


class Miniprogram
{
    public $appid;
    public $app_secret;

    public function __construct($appid, $app_secret)
    {
        $this->appid = $appid;
        $this->app_secret = $app_secret;
    }

    public function getUserInfo($code)
    {
        $url = "https://api.weixin.qq.com/sns/jscode2session?appid={$this->appid}&secret={$this->app_secret}&js_code={$code}&grant_type=authorization_code";
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_PORT , 443);
        curl_setopt($curl, CURLOPT_VERBOSE, true);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_SSLVERSION, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 信任任何证书
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($curl);
        if(!curl_errno($curl)){
            return json_decode($data,true);
        } else {
            return ErrorCode::$NetworkError;
        }
    }

    public function decryptData($encryptedData, $iv, $session_key)
    {
        $pc = new WXBizDataCrypt($this->appid, $session_key);

        $errCode = $pc->decryptData($encryptedData, $iv, $data);

        if ($errCode === ErrorCode::$OK) {
            return json_decode($data, true);
        }

        return $errCode;
    }
}