<?php
namespace app\home\controller;

require 'vendor/autoload.php';

use app\common\ResponseCode;
use app\common\WeChat;
use app\home\model\OrderModel;
use core\basic\Controller;
use core\basic\Config;
use core\basic\Log;
use EasyWeChat\Factory as WeChatFactory;

class CallbackController extends Controller
{
    public function alipay(){

    }

    //支付回调
    public function wechat(){
        $config = Config::get('wechat',true);
        $app = WeChatFactory::payment($config);
        $response = $app->handlePaidNotify(function($message, $fail) use ($app){
            // 使用通知里的 "微信支付订单号" 或者 "商户订单号" 去自己的数据库找到订单
            $orderNo = $message['out_trade_no'];
            $orderModel = new OrderModel();
            $order = $orderModel->getOrderByNo($orderNo);
            Log::info('订单号: '.$orderNo.'--微信支付回调');
            if (!$order || $order->status==1) { // 如果订单不存在 或者 订单已经支付过了
                return true; // 通知微信，已经处理完了，订单没找到，不需要再调用该接口通知我了
            }
            ///////////// <- 建议在这里调用微信的【订单查询】接口查一下该笔订单的情况，确认是已经支付 /////////////
            $tradeInfo = $app->order->queryByOutTradeNumber($orderNo);
            $data['payment_type'] = 2;
            if ($message['return_code'] === 'SUCCESS') { // return_code 表示通信状态，不代表支付状态
                if ($message['result_code'] === 'SUCCESS' && $tradeInfo['trade_state'] === 'SUCCESS') { // 用户是否支付成功
                    $data['payment_time'] = get_datetime(); // 更新支付时间为当前时间
                    $data['status'] = 1;
                } elseif (array_get($message, 'result_code') === 'FAIL') { // 用户支付失败

                }
            } else {
                return $fail('通信失败，请稍后再通知我');
            }
            $orderModel->modifyOrder($orderNo,$data); // 保存订单
            return true; // 返回处理完成
        });
        $response->send(); // return $response;
    }

    //登录回调
    public function wechat_oauth_callback(){
        echo WeChat::getWeChatLoginQRCodeUrl();
    }
}