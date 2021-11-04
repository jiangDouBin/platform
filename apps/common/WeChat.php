<?php

namespace app\common;

require 'vendor/autoload.php';

use core\basic\Config;
use EasyWeChat\Factory;

class WeChat
{
    public static function getWeChatQRCodeUrl($order){
        $config = Config::get('wechat',true);
        $app = Factory::payment($config);
        return $app->order->unify([
            'trade_type' => 'NATIVE',
            'out_trade_no' => $order->order_no,
            'body' => 'super',
            'total_fee' => $order->amount * 100, // 微信支付的单位是分
            'time_start' => time(),
            'time_expire' => date("YmdHis", time() + 600),
            'goods_tag' => $order->title,
            'product_id' => $order->product_id,
        ]);
    }
}
