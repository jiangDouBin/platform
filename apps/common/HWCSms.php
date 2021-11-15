<?php

namespace app\common;

require 'vendor/autoload.php';

use core\basic\Config;
use GuzzleHttp\Psr7;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class HWCSms
{
    public static function SendSms(array $mobiles, string $yzm)
    {
        //必填,请参考"开发准备"获取如下数据,替换为实际值
        $url = Config::get('hwc.url'); //APP接入地址+接口访问URI
        $APP_KEY = Config::get('hwc.app_key'); //APP_Key
        $APP_SECRET = Config::get('hwc.app_secret'); //APP_Secret
        $sender = Config::get('hwc.sender'); //国内短信签名通道号或国际/港澳台短信通道号
        $TEMPLATE_ID = Config::get('hwc.template_id'); //模板ID

        //条件必填,国内短信关注,当templateId指定的模板类型为通用模板时生效且必填,必须是已审核通过的,与模板类型一致的签名名称
        //国际/港澳台短信不用关注该参数
        $signature = "华为云短信测试"; //签名名称

        //必填,全局号码格式(包含国家码),示例:+86151****6789,+86152****7890,多个号码之间用英文逗号分隔
        array_walk($mobiles, function(&$value, $key) { $value = '+86'.$value; } );
        $receiver = join(',', $mobiles); //短信接收人号码

        //选填,短信状态报告接收地址,推荐使用域名,为空或者不填表示不接收状态报告
        $statusCallback = '';

        /**
         * 选填,使用无变量模板时请赋空值 $TEMPLATE_PARAS = '';
         * 单变量模板示例:模板内容为"您的验证码是${1}"时,$TEMPLATE_PARAS可填写为 '["369751"]'
         * 双变量模板示例:模板内容为"您有${1}件快递请到${2}领取"时,$TEMPLATE_PARAS可填写为'["3","人民公园正门"]'
         * 模板中的每个变量都必须赋值，且取值不能为空
         * 查看更多模板格式规范:产品介绍>模板和变量规范
         * @var string $TEMPLATE_PARAS
         */
        
        $TEMPLATE_PARAS = "[".$yzm."]"; //模板变量，此处以单变量验证码短信为例，请客户自行生成6位验证码，并定义为字符串类型，以杜绝首位0丢失的问题（例如：002569变成了2569）。
        // print_r($TEMPLATE_PARAS);
        // print_r('["369751"]');
        // $TEMPLATE_PARAS = '["369751"]'; 
        $client = new Client();
        try {
            $response = $client->request('POST', $url, [
                'form_params' => [
                    'from' => $sender,
                    'to' => $receiver,
                    'templateId' => $TEMPLATE_ID,
                    'templateParas' => $TEMPLATE_PARAS,
                    'statusCallback' => $statusCallback,
                    //'signature' => $signature //使用国内短信通用模板时,必须填写签名名称
                ],
                'headers' => [
                    'Authorization' => 'WSSE realm="SDP",profile="UsernameToken",type="Appkey"',
                    'X-WSSE' => self::buildWsseHeader($APP_KEY, $APP_SECRET)
                ],
                'verify' => false //为防止因HTTPS证书认证失败造成API调用失败，需要先忽略证书信任问题
            ]);
            echo Psr7\str($response); //打印响应信息
        } catch (RequestException $e) {
            echo $e;
            echo Psr7\str($e->getRequest()), "\n";
            if ($e->hasResponse()) {
                echo Psr7\str($e->getResponse());
            }
        }
    }
    function randStr($len=6,$format='NUMBER') { 
        switch($format) { 
        case 'ALL':
        $chars='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-@#~'; break;
        case 'CHAR':
        $chars='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz-@#~'; break;
        case 'NUMBER':
        $chars='0123456789'; break;
        default :
        $chars='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-@#~'; 
        break;
        }
        mt_srand((double)microtime()*1000000*getmypid()); 
        $password="";
        while(strlen($password)<$len)
           $password.=substr($chars,(mt_rand()%strlen($chars)),1);
        return $password;
        } 
    /**
     * 构造X-WSSE参数值
     * @param string $appKey
     * @param string $appSecret
     * @return string
     */
    private function buildWsseHeader(string $appKey, string $appSecret)
    {
        $now = date('Y-m-d\TH:i:s\Z'); //Created
        $nonce = uniqid(); //Nonce
        $base64 = base64_encode(hash('sha256', ($nonce . $now . $appSecret))); //PasswordDigest
        return sprintf("UsernameToken Username=\"%s\",PasswordDigest=\"%s\",Nonce=\"%s\",Created=\"%s\"",
            $appKey, $base64, $nonce, $now);
    }
}
