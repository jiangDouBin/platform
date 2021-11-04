<?php
namespace app\home\controller;

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

    public function alipayQrCode(){

    }

    public function wechatQrCode(){
        $orderId = get('id','int',0,'',0);
        $orderModel = new OrderModel();
        if($order=$orderModel->getOrder($orderId)){
            if ($order->status != 0)
                return responseJson(ResponseCode::HTTP_BAD_REQUEST,'订单不可支付');
            $result = WeChat::getWeChatQRCodeUrl($order);
            if($result['return_code'] != 'SUCCESS')
                return responseJson(ResponseCode::HTTP_BAD_REQUEST,'订单不可支付');
            $url = urlencode($result['code_url']);
            $qrCode = QrCode::createQrCode($url,'使用微信扫描二维码进行支付');
            return responseJson(ResponseCode::HTTP_OK,'成功',['imgUri' => $qrCode->getDataUri()]);
        }else
            return responseJson(ResponseCode::HTTP_BAD_REQUEST,'订单不存在');
    }

    private function checkProduct($contentId){
        $productModel = new ProductModel();
        $product = $productModel->getContent($contentId);
        if(!$product)
            return false;
        return true;
    }
}