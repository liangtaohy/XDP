<?php
/**
 * 微信支付
 *
 * User: Lotushy (liangtaohy@gmail.com)
 * Date: 2018/10/22
 * Time: 下午5:44
 */

namespace Xdp\Pay\WxPay;

use Exception;
use Xdp\Pay\WxPay\Lib\WxPayException;
use Xdp\Pay\WxPay\Lib\WxPayJsApiPay;
use Xdp\Pay\WxPay\Lib\WxPayApi;

class WxPay
{
    use Traits\WxDataValidatorTrait;

    protected $config;
    protected $input;

    protected $openid;
    protected $body;
    protected $attach;
    protected $out_trade_no;
    protected $total_fee;
    protected $time_start;
    protected $time_expire;
    protected $goods_tag;
    protected $notify_url;
    protected $trade_type;

    public function __construct($config, $openid)
    {
        $this->config = new WxPayConfig($config);;
        $this->openid = $openid;

        $this->input = new Lib\WxPayUnifiedOrder();
    }


    public function setBody($body)
    {
        $this->checkString($body, 0, 128);

        $this->input->SetBody($body);

        return $this;
    }

    public function setDetail($detail)
    {
        $this->checkString($detail, 0, 6000);

        $this->input->SetDetail($detail);
    }

    public function setAttach($attach)
    {
        $this->checkString($attach, 0, 127);

        $this->input->SetAttach($attach);

        return $this;
    }

    public function setOutTradeNo($out_trade_no)
    {
        $this->checkOutTradeNo($out_trade_no);

        $this->input->SetOut_trade_no($out_trade_no);

        return $this;
    }

    public function setFeeType($fee_type)
    {
        $this->input->SetFee_type($fee_type);

        return $this;
    }

    public function setTotalFee($total_fee)
    {
        $this->checkTotalFee($total_fee);

        $this->input->SetTotal_fee($total_fee);

        return $this;
    }

    public function setGoodsTag($goods_tag)
    {
        $this->checkString($goods_tag, 0, 32);

        $this->input->SetGoods_tag($goods_tag);

        return $this;
    }

    public function setNotifyUrl($notify_url)
    {
        $this->checkString($notify_url, 10, 256);

        if (strpos($notify_url, '?') !== false) {
            throw new \InvalidArgumentException("invalid argument notify_url[{$notify_url}]");
        }

        $this->input->SetNotify_url($notify_url);

        return $this;
    }

    public function setTradeType($trade_type)
    {
        WxCommon::checkTradeType($trade_type);

        $this->input->SetTrade_type($trade_type);

        return $this;
    }

    public function setProductId($product_id)
    {
        $this->checkString($product_id, 0, 32);
        $this->input->SetProduct_id($product_id);

        return $this;
    }

    public function setOpenId($openid)
    {
        $this->checkOpenId($openid);

        $this->input->SetOpenid($openid);

        return $this;
    }

    public function setLimitPay($limit_pay)
    {
        $this->checkString($limit_pay, 0, 32);

        $this->input->SetLimit_pay($limit_pay);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAttach()
    {
        return $this->attach;
    }

    /**
     * jsApi下单接口
     * @return null|string
     * @throws \InvalidArgumentException
     */
    public function jsApi()
    {
        if(!$this->input->IsTrade_typeSet()) {
            throw new \InvalidArgumentException("trade_type参数必须指定");
        }

        if ($this->input->GetTrade_type() !== "JSAPI") {
            throw new \InvalidArgumentException("jsapi的trade_type必须为JSAPI");
        }

        try{
            $order = Lib\WxPayApi::unifiedOrder($this->config, $this->input);
            $jsApiParameters = $this->getJsApiParameters($order);
        } catch(Exception $e) {
            //Log::ERROR(json_encode($e));
            echo $e->getMessage() . PHP_EOL;
            return null;
        }

        echo "test 183" . PHP_EOL;

        return $jsApiParameters;
    }

    /**
     * 获取jsapi支付的参数
     *
     * @param array $UnifiedOrderResult 统一支付接口返回的数据
     * @return string json数据，可直接填入js函数作为参数
     * @throws WxPayException
     */
    protected function getJsApiParameters($UnifiedOrderResult)
    {
        if(!array_key_exists("appid", $UnifiedOrderResult)
            || !array_key_exists("prepay_id", $UnifiedOrderResult)
            || $UnifiedOrderResult['prepay_id'] == "")
        {
            throw new WxPayException("参数错误");
        }

        $jsapi = new WxPayJsApiPay();
        $jsapi->SetAppid($UnifiedOrderResult["appid"]);
        $timeStamp = time();
        $jsapi->SetTimeStamp("$timeStamp");
        $jsapi->SetNonceStr(WxPayApi::getNonceStr());
        $jsapi->SetPackage("prepay_id=" . $UnifiedOrderResult['prepay_id']);

        $jsapi->SetPaySign($jsapi->MakeSign($this->config));
        $parameters = json_encode($jsapi->GetValues());
        return $parameters;
    }
}