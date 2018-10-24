<?php
/**
 * 微信退款
 *
 * User: Lotushy (liangtaohy@gmail.com)
 * Date: 2018/10/23
 * Time: 上午9:43
 */

namespace Xdp\Pay\WxPay;

use Xdp\Pay\WxPay\Lib\WxPayApi;
use Xdp\Pay\WxPay\Lib\WxPayRefund;

class WxRefund
{
    use Traits\WxDataValidatorTrait;

    /**
     * 微信支付相关配置
     *
     * @var WxPayConfig
     */
    private $config;

    /**
     * Refund data
     *
     * @var WxPayRefund
     */
    protected $wx_pay_refund;

    /**
     * WxRefund constructor.
     * @param $config
     */
    public function __construct($config)
    {
        $this->config = new WxPayConfig($config);
        $this->wx_pay_refund = new WxPayRefund();
    }

    /**
     * 微信订单号
     *
     * @param $transaction_id
     * @return $this
     */
    public function setTransactionId($transaction_id)
    {
        // transaction_id validator
        $this->checkTransactionId($transaction_id);

        $this->wx_pay_refund->SetTransaction_id($transaction_id);

        return $this;
    }

    /**
     * 商户订单号
     *
     * @param $out_trade_no
     * @return $this
     */
    public function setOutTradeNo($out_trade_no)
    {
        $this->checkOutTradeNo($out_trade_no);

        $this->wx_pay_refund->SetOut_trade_no($out_trade_no);

        return $this;
    }

    /**
     * 商户退款单号
     *
     * @param $out_refund_no
     * @return $this
     */
    public function setOutRefundNo($out_refund_no)
    {
        $this->checkOutRefundNo($out_refund_no);

        $this->wx_pay_refund->SetOut_refund_no($out_refund_no);

        return $this;
    }

    /**
     * 订单金额
     *
     * @param $total_fee
     * @return $this
     */
    public function setTotalFee($total_fee)
    {
        $this->checkTotalFee($total_fee);

        $this->wx_pay_refund->SetTotal_fee($total_fee);

        return $this;
    }

    /**
     * 退款金额
     *
     * @param $refund_fee
     * @return $this
     */
    public function setRefundFee($refund_fee)
    {
        $this->checkRefundFee($refund_fee);

        $this->wx_pay_refund->SetRefund_fee($refund_fee);

        return $this;
    }

    /**
     * 货币种类，目前仅支持CNY.
     *
     * @param string $refund_fee_type
     * @return $this
     */
    public function setRefundFeeType($refund_fee_type = "CNY")
    {
        $this->wx_pay_refund->SetRefund_fee_type($refund_fee_type);

        return $this;
    }

    /**
     * 退款原因
     *
     * @param $refund_desc
     * @return $this
     */
    public function setRefundDesc($refund_desc)
    {
        $this->wx_pay_refund->SetRefund_desc($refund_desc);

        return $this;
    }

    /**
     * 发起退款申请
     *
     * @return Lib\成功时返回，其他抛异常
     */
    public function refund()
    {
        return WxPayApi::refund($this->config, $this->wx_pay_refund, $this->config->GetApiTimeout());
    }
}