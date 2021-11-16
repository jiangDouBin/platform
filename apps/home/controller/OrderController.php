<?php
namespace app\home\controller;

use app\common\Alipay;
use app\common\BasicController;
use app\common\QrCode;
use app\common\ResponseCode;
use app\common\WeChat;
use app\home\model\OrderModel;
use app\home\model\ProductModel;
use core\basic\Model;
use core\basic\Url;

class OrderController extends BasicController
{
    public function __construct()
    {
        // 未登录时跳转到用户登录
        if (!session('pboot_uid')) {
            location(Url::home('member/login'));
        }
    }

    // 下单
    public function addorder() {
        // 未登录时跳转到用户登录
        if (! session('pboot_uid')) {
            location(Url::home('member/login'));
        }
        $productid = $_GET['product_id'];
        if(empty($productid)) {
            error('产品ID: error');
        }else{
            $ProductModel = new ProductModel;
            $result = $ProductModel->getContent($productid);
            if($result) {
                $orderModel = new orderModel();
                $order = $orderModel->getOrderByProductId($productid);
                if($order){
                    $url = '/member/orderinfo?id='.$order->id;
                    location(Url::home($url));
                    return;
                    // error('当前商品已经下单，请勿重复下单', -1);
                }
                // 获取产品信息正常
                $data = array(
                    'order_no' => 'ddd'.date('YmdHis').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))),0,12),
                    'member_id' => session('pboot_uid'),
                    'product_id' =>$result->id,
                    'status' => 0,
                    'amount' =>  $result->ext_price,
                    'payment_type' => 0,
                    'payment_time' => '',
                    'created_time' => get_datetime(),
                    'remark' =>' '
                );

                if($id=$orderModel->addOrders($data)) {
                    $url = '/member/orderinfo?id='.$id;
                    location(Url::home($url));
                }else {
                    error('生成订单失败error');
                }
            }else{
                error('产品信息error');
            }
        }


    }

    //生成支付宝二维码供用户支付
    public function alipayQrCode(){
        $orderId = get('id','int',0,'',0);
        $orderModel = new OrderModel();
        if($order=$orderModel->getOrder($orderId)){
            if ($order->status != 0)
                return responseJson(false,'订单不可支付');
            $result = Alipay::getAlipayPagePay($order);;
            return responseJson(true,'成功',['pay_page' => $result->body]);
        }else
            return responseJson(false,'订单不存在');
    }

    //生成微信二维码供用户支付
    public function wechatQrCode(){
        $orderId = get('id','int',0,'',0);
        $orderModel = new OrderModel();
        if($order=$orderModel->getOrder($orderId)){
            if ($order->status != 0)
                return responseJson(false,'订单不可支付');
            $result = WeChat::getWeChatQRCodeUrl($order);
            if($result['return_code'] != 'SUCCESS')
                return responseJson(false,'订单不可支付');
            $url = $result['code_url'];
            $qrCode = QrCode::createQrCode($url,'使用微信扫描二维码进行支付');
            return responseJson(true,'成功',['imgUri' => $qrCode->getDataUri()]);
        }else
            return responseJson(false,'订单不存在');
    }

    //查询订单支付状态
    public function payResult(){
        $orderId = get('id','int',0,'',0);
        $orderModel = new OrderModel();
        if($order=$orderModel->getOrder($orderId)){
            if ($order->status == 1)
                return responseJson(true,'订单已支付');
        }

        return responseJson(false,'订单未支付');
    }
}