<?php

namespace app\common;

require 'vendor/autoload.php';

use core\basic\Config;
use EasyWeChat\Factory;

class WeChat
{
    //获取登录二维码
    public static function getWeChatLoginQRCodeUrl(){
        $config = Config::get('wechat',true);
        $app = Factory::officialAccount($config);
        $url = $app->oauth->scopes(['snsapi_userinfo'])->redirect('https://www.diduoduotouzi.com/callback/wechat_oauth_callback')->getTargetUrl();
        return $url;
    }

    //获取支付二维码
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
