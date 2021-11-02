<?php

namespace app\common;

require 'vendor/autoload.php';

use core\basic\Config;
use EasyWeChat\Factory;

class WeChat
{
    public static function getOpenPlatFormUrl(){
        $config = Config::get('wechat',true);
        $app = Factory::payment($config);
        return $app->order->unify([
            'trade_type' => 'NATIVE',
            'out_trade_no' => 'asdf',
            'body' => 'super',
            'total_fee' => '100',
            'time_start' => time(),
            'time_expire' => date("YmdHis", time() + 600),
            'goods_tag' => 'hahah',
            'notify_url' => 'http://paysdk.weixin.qq.com/notify.php',
            'product_id' => '123'
        ]);
    }
}
