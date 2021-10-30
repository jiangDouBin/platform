<?php
/**
 * @copyright (C)2016-2099 Hnaoyun Inc.
 * @author XingMeng
 * @email hnxsh@foxmail.com
 * @date 2020年06月26日
 *  会员前台控制器
 */

namespace app\home\controller;

require 'vendor/autoload.php';

use app\common\BasicController;
use app\home\model\MemberModel;
use core\basic\Url;
use Alipay\EasySDK\Kernel\Factory;
use Alipay\EasySDK\Kernel\Config as AlipayConfig;
use Alipay\EasySDK\Kernel\Util\ResponseChecker;

class AlipayController extends BasicController
{
    public function __construct()
    {
        Factory::setOptions($this->getOptions());
    }

    // 支付页面
    public function alipay()
    {
        // 未登录时跳转到登陆页面
        if (!session('pboot_uid')) {
            location(Url::home('member/login'));
        }

        try {
            $result = Factory::payment()->page()->pay("iPhone6 16G", "20200326235526001", "88.88", "");
            echo $result->body;
        } catch (Exception $e) {
            echo "调用失败，". $e->getMessage(). PHP_EOL;;
        }
    }

    public function _empty()
    {
        _404('您访问的地址不存在，请核对再试！');
    }

    private function getOptions()
    {
        $options = new AlipayConfig();
        $options->protocol = 'https';
        $options->gatewayHost = 'openapi.alipay.com';
        $options->signType = 'RSA2';

        $options->appId = '2016080401704740';

        // 为避免私钥随源码泄露，推荐从文件中读取私钥字符串而不是写入源码中
        $options->merchantPrivateKey = 'MIIEowIBAAKCAQEAkTvL49c/AeB3F1P+fOpBZj9Uaip2BgEI4YlC0DB15mjYGOblmmmVv1CSI2GWKctr5WoHPfCJLYChSe8rVPuYraOHnFXyT+AXYXDiKKvczglESmiW6kK4vaZb+nVbhaHkH/N3s+5zi9RwlQp8ltXHgKSZ3CeVihUuUv56IiBAN7Ra0gln569bEZw99av5oKWezoqPLksK9H4btz7KMZc5S1csaPljKA68E3PKmXI+RsobXdy9fCaJr+Y+4Gem1Jutvfuiz27FpRbyd7+qhUmU6Mn25iqxA/T+il7avXO5tDmCl6I3wCrSPVHTYKsEZQI1rhQxffuQ5m5CNYqJmvV0GQIDAQABAoIBACeKgzl2GgZ1yZbFXI0/7ixbY61AAEAkOfnFuDYca40e0G7/NlXzFz0uP4t4fzzD0I12b6BSg9aqpb8OadrKYUgtNLfAwqcymMsDw99U41oJNdmYXjZLkia4S2SGXTOl3wk/7UBE1JqmbTx2zXL3o0iICAfjkxg0KheYh0IRS8qeWLXHB9NZHCBaI+BCKgmJhFHPHha7hVBaO2MEHAH8RVOF18jsjQly1XoM03fvtUBz2PcR3ZwGlq+IymUgZYbTaqvCwOqjNbxh41ni+a3hUxq8+JS6mGkrmfSKE04oiIBSIeGmkxEZq94Ytgo1xDDM7tUdZo+EK2RkQYlmgiwg+d0CgYEA9z+bS2Joi1HCb3QrqmnDkPjOyVbupoJTDFzmOeBlu1v8wsXnWzI7A5l8r0N5SEpn+rkvTtSFvRDlleYSz3ehtMBm1UTlbORWYE+hj1WBTpORxBcpbW8Pnr1/ZgmTCOqDzSoiD+GPyZwqeM7FHVyyA0BXGUgnYL/QCF5H/4oi+hcCgYEAll/NT9XwO5Pbo81pm4+dD0PUMYji7Ur9+E63rAyb4zOdkXf3VMpXj0IT867hbbKt8oSHB2motXZzOYsHDgmtLavo9G5mRsC6mzLjRh20YAre0vykMM3l3Df2DiFHViS0vplL68mXKQ7RERkjms+nXMu15ZgOBgwx/Y7kI8nfUU8CgYA5+1+mwRA1Q9ouGvc2gpELSV3tF4bl44AoUQzom7gfxQW0g4aq+0+dm3wC+fbSPii+QnlWqj4mxXShv4+/uZVhdxFmiD6pV67t7R69J1conzC65Jehgz8NgfEDc9FYcO6xyWSthr8aj5XiONM+/IL+gjMqyaH6bWES5VFLGxDyxwKBgQCMV7s5kJTWNcfe176BpgZSkd5/oJ8SElR3o5f+ZgIziR+8/XcDVpljpasTWhsgk4uO9StEutLvES62/M9HxbYwEuqm/kZMMGG4qUS+Usjefia6SXo/5lpqLnxhcaOvfTCHVnEqDYobkq2CxLCbMsSjcahBMKmd8VHSsMKxuB3JNwKBgAiiMKAkNW3jBlbzCJS28+waeSo8lnqOCzIwyUaVaUcgwvuVWsMRWQlimBcGIi4i/33qzXcR5O6fsO46Y6q0q+3TNQBLN65ZGGNnD1NofOd/xBS8ZNsK5IoMlEqeA6ms8YcB7HbwRPNZMnO7ssF9zmc1iZIYEX6JdoA1THEpUX4x';

        $options->alipayCertPath = '<-- 请填写您的支付宝公钥证书文件路径，例如：/foo/alipayCertPublicKey_RSA2.crt -->';
        $options->alipayRootCertPath = '<-- 请填写您的支付宝根证书文件路径，例如：/foo/alipayRootCert.crt" -->';
        $options->merchantCertPath = '<-- 请填写您的应用公钥证书文件路径，例如：/foo/appCertPublicKey_2019051064521003.crt -->';

        //注：如果采用非证书模式，则无需赋值上面的三个证书路径，改为赋值如下的支付宝公钥字符串即可
        // $options->alipayPublicKey = '<-- 请填写您的支付宝公钥，例如：MIIBIjANBg... -->';

        //可设置异步通知接收服务地址（可选）
        $options->notifyUrl = "<-- 请填写您的支付类接口异步通知接收服务地址，例如：https://www.test.com/callback -->";

        //可设置AES密钥，调用AES加解密相关接口时需要（可选）
        $options->encryptKey = "<-- 请填写您的AES密钥，例如：aa4BtZ4tspm2wnXLb1ThQA== -->";

        return $options;
    }
}