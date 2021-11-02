<?php

namespace app\common;

require 'vendor/autoload.php';

use core\basic\Config;
use core\basic\Log;
use Alipay\EasySDK\Kernel\Factory;
use Alipay\EasySDK\Kernel\Config as AlipayConfig;

class Alipay
{
    // 获取网页支付对象
    public static function getAlipayPagePay(){
        try {
            Factory::setOptions(self::getOptions());
            $response = Factory::payment()->page()->pay("iPhone6 16G", "20200326235526001", "88.88", "");
            return $response;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return false;
        }
    }

    // 初始化支付宝配置
    private static function getOptions()
    {
        $options = new AlipayConfig();
        $options->protocol = 'https';
        $options->gatewayHost = 'openapi.alipay.com';
        $options->signType = 'RSA2';

        $options->appId = Config::get('alipay.app_id');

        // 为避免私钥随源码泄露，推荐从文件中读取私钥字符串而不是写入源码中
        $options->merchantPrivateKey = Config::get('alipay.merchant_private_key');

        //注：如果采用证书签名模式，则需要配置以下三个证书路径，而无需配置支付宝公钥字符串
        //$options->alipayCertPath = '<-- 请填写您的支付宝公钥证书文件路径，例如：/foo/alipayCertPublicKey_RSA2.crt -->';
        //$options->alipayRootCertPath = '<-- 请填写您的支付宝根证书文件路径，例如：/foo/alipayRootCert.crt" -->';
        //$options->merchantCertPath = '<-- 请填写您的应用公钥证书文件路径，例如：/foo/appCertPublicKey_2019051064521003.crt -->';

        //注：如果采用非证书模式，则无需赋值上面的三个证书路径，改为赋值如下的支付宝公钥字符串即可
        $options->alipayPublicKey = Config::get('alipay.alipay_public_key');

        //可设置异步通知接收服务地址（可选）
        $options->notifyUrl = '';

        //可设置AES密钥，调用AES加解密相关接口时需要（可选）
        //$options->encryptKey = "<-- 请填写您的AES密钥，例如：aa4BtZ4tspm2wnXLb1ThQA== -->";

        return $options;
    }
}
