<?php

namespace app\home\controller;

require 'vendor/autoload.php';

use app\common\Alipay;
use app\common\ResponseCode;
use app\common\WeChat;
use app\home\model\MemberModel;
use app\home\model\OrderModel;
use core\basic\Controller;
use core\basic\Config;
use core\basic\Log;
use core\basic\Model;
use core\basic\Url;
use EasyWeChat\Factory as WeChatFactory;
use Alipay\EasySDK\Kernel\Factory as AlipayFactory;

class CallbackController extends Controller
{
    //支付宝支付回调
    public function alipay()
    {
        AlipayFactory::setOptions(Alipay::getOptions());
        // 异步通知验签
        $result = AlipayFactory::payment()->common()->verifyNotify($_POST);
        if ($result) {
            //商户订单号
            $orderNo = $_POST['out_trade_no'];
            $orderModel = new OrderModel();
            $order = $orderModel->getOrderByNo($orderNo);
            Log::info('订单号: ' . $orderNo . '--支付宝支付回调');
            if (!$order || $order->status == 1) { // 如果订单不存在 或者 订单已经支付过了
                echo "success"; // 通知支付宝，已经处理完了，订单没找到，不需要再调用该接口通知我了
            }

            //支付宝交易号
            $trade_no = $_POST['trade_no'];

            //交易状态
            $trade_status = $_POST['trade_status'];
            if ($trade_status == 'TRADE_FINISHED' || $trade_status == 'TRADE_SUCCESS') {
                $data['payment_type'] = 1;
                if ($_POST['total_amount'] === $order['amount']) {
                    $data['payment_time'] = get_datetime(); // 更新支付时间为当前时间
                    $data['status'] = 1;
                } else {
                    echo "fail";
                }
                $orderModel->modifyOrder($orderNo, $data); // 保存订单
                //注意：
                //退款日期超过可退款期限后（如三个月可退款），支付宝系统发送该交易状态通知
            }
            echo "success";    //请不要修改或删除
        } else
            //验证失败
            echo "fail";
    }

    //微信支付回调
    public function wechat()
    {
        $config = Config::get('wechat_offiaccount', true);
        $app = WeChatFactory::payment($config);
        $response = $app->handlePaidNotify(function ($message, $fail) use ($app) {
            // 使用通知里的 "微信支付订单号" 或者 "商户订单号" 去自己的数据库找到订单
            $orderNo = $message['out_trade_no'];
            $orderModel = new OrderModel();
            $order = $orderModel->getOrderByNo($orderNo);
            Log::info('订单号: ' . $orderNo . '--微信支付回调');
            if (!$order || $order->status == 1) { // 如果订单不存在 或者 订单已经支付过了
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
            $orderModel->modifyOrder($orderNo, $data); // 保存订单
            return true; // 返回处理完成
        });
        $response->send(); // return $response;
    }

    //微信登录回调
    public function wechat_oauth_callback()
    {
        $config = Config::get('wechat_oplatform', true);
        $app = WeChatFactory::officialAccount($config);
        $oauth = $app->oauth;
        // 获取 OAuth 授权结果用户信息
        $user = $oauth->user();
        // $user 可以用的方法:
        $wxid = $user->getId();  // 对应微信的 OPENID
        $nickName = $user->getNickname(); // 对应微信的 nickname
        $avatar = $user->getAvatar(); // 头像网址
        // $user->getOriginal(); // 原始API返回的结果
        // $user->getToken(); // access_token， 比如用于地址共享时使用

        $model = new MemberModel();
        $member = $model->getMemberByOpenId($wxid);
        session('pboot_wxid', $wxid);
        session('pboot_nick_name', $nickName);
        session('pboot_avatar', $avatar);
        if($member){ //已经绑定微信的，直接登录
            session('pboot_uid', $member->id);
            session('pboot_ucode', $member->ucode);
            session('pboot_username', $member->username);
            session('pboot_useremail', $member->useremail);
            session('pboot_usermobile', $member->usermobile);
            session('pboot_gid', $member->gid);
            session('pboot_gcode', $member->gcode);
            session('pboot_gname', $member->gname);

            location(Url::home('/member/ucenter'));
        }else{ //未绑定微信的，跳转到注册页面
            location(Url::home('/member/bind'));
        }
    }
}