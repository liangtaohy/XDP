<?php
/**
 * Created by PhpStorm.
 * User: Lotushy (liangtaohy@gmail.com)
 * Date: 2018/10/22
 * Time: 下午4:27
 */
namespace Xdp\Pay\WxPay;

/**
 * Class WxPayConfig
 * @example config example
 *  'appid' => 'wx460fc1efdfa5edfc',
 *  'secret' => '337aa836eaa5f344e8ff84c9e35685b8',
 *  'key' => '支付密钥(商户平台设置)',
 *  'mch_id' => '商户号'
 * @package Xdp\Pay\WxPay
 */

class WxPayConfig extends Lib\WxPayConfigInterface
{
    private $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    //=======【基本信息设置】=====================================
    /**
     *
     * 微信公众号信息配置
     *
     * APPID：绑定支付的APPID（必须配置，开户邮件中可查看）
     *
     * MCHID：商户号（必须配置，开户邮件中可查看）
     *
     */
    public function GetAppId()
    {
        return $this->config['appid'];
    }

    public function GetMerchantId()
    {
        return $this->config['mch_id'];
    }


    //=======【支付相关配置：支付成功回调地址/签名方式】===================================
    /**
     * 签名和验证签名方式， 支持md5和sha256方式
     **/
    public function GetNotifyUrl()
    {
        return $this->config['notify_url'];
    }

    /**
     * 默认值为'HMAC-SHA256'
     *
     * @return mixed|string
     */
    public function GetSignType()
    {
        return isset($this->config['sign_type']) ? $this->config['sign_type'] : 'HMAC-SHA256';
    }

    //=======【curl代理设置】===================================
    /**
     * 本例程通过curl使用HTTP POST方法，此处可修改代理服务器，
     * 默认CURL_PROXY_HOST=0.0.0.0和CURL_PROXY_PORT=0，此时不开启代理（如有需要才设置）
     * @param $proxyHost
     * @param $proxyPort
     */
    public function GetProxy(&$proxyHost, &$proxyPort)
    {
        $proxyHost = "0.0.0.0";
        $proxyPort = 0;
    }


    //=======【上报信息配置】===================================
    /**
     * 不会影响接口调用流程），开启上报之后，方便微信监控请求调用的质量，建议至少
     * 开启错误上报。
     * 上报等级，0.关闭上报; 1.仅错误出错上报; 2.全量上报
     * @return int
     */
    public function GetReportLevenl()
    {
        return isset($this->config['report_level']) ? $this->config['report_level'] : 1;
    }


    //=======【商户密钥信息-需要业务方继承】===================================
    /*
     * KEY：商户支付密钥，参考开户邮件设置（必须配置，登录商户平台自行设置）, 请妥善保管， 避免密钥泄露
     * 设置地址：https://pay.weixin.qq.com/index.php/account/api_cert
     *
     * APPSECRET：公众帐号secert（仅JSAPI支付的时候需要配置， 登录公众平台，进入开发者中心可设置）， 请妥善保管， 避免密钥泄露
     * 获取地址：https://mp.weixin.qq.com/advanced/advanced?action=dev&t=advanced/dev&token=2005451881&lang=zh_CN
     * @var string
     */
    public function GetKey()
    {
        return $this->config['key'];
    }

    public function GetAppSecret()
    {
        return $this->config['app_secret'];
    }


    //=======【证书路径设置-需要业务方继承】=====================================
    /**
     * 证书路径,注意应该填写绝对路径（仅退款、撤销订单时需要，可登录商户平台下载，
     * API证书下载地址：https://pay.weixin.qq.com/index.php/account/api_cert，下载之前需要安装商户操作证书）
     * 注意:
     * 1.证书文件不能放在web服务器虚拟目录，应放在有访问权限控制的目录中，防止被他人下载；
     * 2.建议将证书文件名改为复杂且不容易猜测的文件名；
     * 3.商户服务器要做好病毒和木马防护工作，不被非法侵入者窃取证书文件。
     * @param $sslCertPath
     * @param $sslKeyPath
     */
    public function GetSSLCertPath(&$sslCertPath, &$sslKeyPath)
    {
        $sslCertPath = $this->config['ssl_cert_path'];
        $sslKeyPath = $this->config['ssl_key_path'];
    }

    public function GetApiTimeout()
    {
        return $this->config['api_timeout'] ?? 6;
    }
}