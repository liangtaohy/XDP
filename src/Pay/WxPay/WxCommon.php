<?php
/**
 * Created by PhpStorm.
 * User: Lotushy (liangtaohy@gmail.com)
 * Date: 2018/10/23
 * Time: 下午3:46
 */

namespace Xdp\Pay\WxPay;


class WxCommon
{
    const TRADE_TYPE_JSAPI  = 'JSAPI';
    const TRADE_TYPE_NATIVE = 'NATIVE';
    const TRADE_TYPE_APP    = 'APP';
    const TRADE_TYPE_MICROPAY   = 'MICROPAY';

    public static $TradeType = [
        self::TRADE_TYPE_JSAPI, self::TRADE_TYPE_APP, self::TRADE_TYPE_NATIVE, self::TRADE_TYPE_MICROPAY
    ];

    public static function checkTradeType(string $trade_type)
    {
        if (!in_array($trade_type, self::$TradeType)) {
            throw new \InvalidArgumentException("invalid argument trade_type[$trade_type]");
        }
    }
}