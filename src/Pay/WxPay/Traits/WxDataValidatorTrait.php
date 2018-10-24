<?php
/**
 * 微信数据
 *
 * User: Lotushy (liangtaohy@gmail.com)
 * Date: 2018/10/23
 * Time: 下午1:15
 */
namespace Xdp\Pay\WxPay\Traits;

trait WxDataValidatorTrait
{
    /**
     * 检查微信用户openid
     *
     * @param string $openid
     */
    public function checkOpenId(string $openid)
    {
        if (!preg_match("/^[0-9a-zA-Z_]{5,128}$/i", $openid, $matches)) {
            throw new \InvalidArgumentException("invalid openid[{$openid}]");
        }
    }

    /**
     * 微信订单号
     *
     * @param string $transaction_id
     * @throws \InvalidArgumentException
     */
    public function checkTransactionId(string $transaction_id)
    {
        if (!preg_match("/^[0-9a-zA-Z]{10,64}$/i", $transaction_id, $matches)) {
            throw new \InvalidArgumentException("invalid transaction_id[{$transaction_id}]");
        }
    }

    /**
     * 商户订单号
     *
     * @param string $out_trade_no 商户订单号
     * @throws \InvalidArgumentException
     */
    public function checkOutTradeNo(string $out_trade_no)
    {
        if (!preg_match("/^[0-9a-zA-Z_\-\|]{10,64}$/i", $out_trade_no, $matches)) {
            throw new \InvalidArgumentException("invalid out_trade_no[{$out_trade_no}]");
        }
    }

    /**
     * 商户退款单号
     *
     * @param string $out_refund_no
     * @throws \InvalidArgumentException
     */
    public function checkOutRefundNo(string $out_refund_no)
    {
        if (!preg_match("/^[0-9a-zA-Z_\-\|]{10,64}$/i", $out_refund_no, $matches)) {
            throw new \InvalidArgumentException("invalid out_refund_no[{$out_refund_no}]");
        }
    }

    /**
     * 订单总金额，单位为分，只能为整数
     *
     * @param $total_fee
     * @return $this
     */
    public function checkTotalFee($total_fee)
    {
        if (is_string($total_fee) && !preg_match("/^[0-9a-zA-Z]{10,64}$/i", $total_fee, $matches)) {
            throw new \InvalidArgumentException("invalid total_fee[{$total_fee}]");
        }

        $type = gettype($total_fee);

        if ($type == "string") {
            if (!preg_match("/^[0-9a-zA-Z]{10,64}$/i", $total_fee, $matches)) {
                throw new \InvalidArgumentException("invalid total_fee[{$total_fee}]");
            }
        } elseif ($type == "double") {
            throw new \InvalidArgumentException("invalid total_fee[{$total_fee}]");
        }

        if ($total_fee < 0) {
            throw new \InvalidArgumentException("invalid total_fee[{$total_fee}]");
        }
    }

    public function checkRefundFee($refund_fee)
    {
        if (is_string($refund_fee) && !preg_match("/^[0-9a-zA-Z]{10,64}$/i", $refund_fee, $matches)) {
            throw new \InvalidArgumentException("invalid refund_fee[{$refund_fee}]");
        }

        $type = gettype($refund_fee);

        if ($type == "string") {
            if (!preg_match("/^[0-9a-zA-Z]{10,64}$/i", $refund_fee, $matches)) {
                throw new \InvalidArgumentException("invalid refund_fee[{$refund_fee}]");
            }
        } elseif ($type == "double") {
            throw new \InvalidArgumentException("invalid refund_fee[{$refund_fee}]");
        }
    }

    public function checkString(string $str, $minLen, $maxLen)
    {
        $l = strlen($str);
        if ($l < $minLen || $l > $maxLen) {
            throw new \InvalidArgumentException("invalid value, {$str}, len:[{$minLen}:{$maxLen}]");
        }
    }
}