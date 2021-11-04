<?php
return array(
    
    // 授权码，多个授权码使用英文逗号隔开，如：'aaaaa,bbbbb'
    'sn' => 'B2F430B0E9,1E52C2D393',
    
    // 授权用户手机
    'sn_user' => '',
    
    // 模板内容输出缓存开关
    'tpl_html_cache' => 0,
    
    // 模板内容缓存有效时间（秒）
    'tpl_html_cache_time' => 900,
    
    // 会话文件使用网站路径
    'session_in_sitepath' => 1,
    
    // 默认分页大小
    'pagesize' => 15,
    
    // 分页条数字数量
    'pagenum' => 5,
    
    // 访问页面规则，如禁用浏览器、操作系统类型
    'access_rule' => array(
        'deny_bs' => 'MJ12bot,IE6,IE7'
    ),
    
    // 上传配置
    'upload' => array(
        'format' => 'jpg,jpeg,png,gif,xls,xlsx,doc,docx,ppt,pptx,rar,zip,pdf,txt,mp4,avi,flv,rmvb,mp3,otf,ttf',
        'max_width' => '1920',
        'max_height' => ''
    ),
    
    // 缩略图配置
    'ico' => array(
        'max_width' => '1000',
        'max_height' => '1000'
    ),
    
    // 模块模板路径定义
    'tpl_dir' => array(
        'home' => '/template'
    ),

        //华为云通信配置
    'hwc' => array(
    'url' => 'https://rtcsms.cn-north-1.myhuaweicloud.com:10743/sms/batchSendSms/v1', //APP接入地址+接口访问URI
    'app_key' => '0P4emY9AoWKn1SNt3jC825wc0aTg',
    'app_secret' => '02bA9X1wOA19TM38J2bfh0e3451D',
    'sender' => '99200620888880005607', //国内短信签名通道号或国际/港澳台短信通道号
    'template_id' => '84c118acf5fb431984a65f2c7210cff6' //模板ID
),

    //支付宝配置
    'alipay' => array(
    'app_id' => '2021002189692734',
    'merchant_private_key' => 'MIIEowIBAAKCAQEAkTvL49c/AeB3F1P+fOpBZj9Uaip2BgEI4YlC0DB15mjYGOblmmmVv1CSI2GWKctr5WoHPfCJLYChSe8rVPuYraOHnFXyT+AXYXDiKKvczglESmiW6kK4vaZb+nVbhaHkH/N3s+5zi9RwlQp8ltXHgKSZ3CeVihUuUv56IiBAN7Ra0gln569bEZw99av5oKWezoqPLksK9H4btz7KMZc5S1csaPljKA68E3PKmXI+RsobXdy9fCaJr+Y+4Gem1Jutvfuiz27FpRbyd7+qhUmU6Mn25iqxA/T+il7avXO5tDmCl6I3wCrSPVHTYKsEZQI1rhQxffuQ5m5CNYqJmvV0GQIDAQABAoIBACeKgzl2GgZ1yZbFXI0/7ixbY61AAEAkOfnFuDYca40e0G7/NlXzFz0uP4t4fzzD0I12b6BSg9aqpb8OadrKYUgtNLfAwqcymMsDw99U41oJNdmYXjZLkia4S2SGXTOl3wk/7UBE1JqmbTx2zXL3o0iICAfjkxg0KheYh0IRS8qeWLXHB9NZHCBaI+BCKgmJhFHPHha7hVBaO2MEHAH8RVOF18jsjQly1XoM03fvtUBz2PcR3ZwGlq+IymUgZYbTaqvCwOqjNbxh41ni+a3hUxq8+JS6mGkrmfSKE04oiIBSIeGmkxEZq94Ytgo1xDDM7tUdZo+EK2RkQYlmgiwg+d0CgYEA9z+bS2Joi1HCb3QrqmnDkPjOyVbupoJTDFzmOeBlu1v8wsXnWzI7A5l8r0N5SEpn+rkvTtSFvRDlleYSz3ehtMBm1UTlbORWYE+hj1WBTpORxBcpbW8Pnr1/ZgmTCOqDzSoiD+GPyZwqeM7FHVyyA0BXGUgnYL/QCF5H/4oi+hcCgYEAll/NT9XwO5Pbo81pm4+dD0PUMYji7Ur9+E63rAyb4zOdkXf3VMpXj0IT867hbbKt8oSHB2motXZzOYsHDgmtLavo9G5mRsC6mzLjRh20YAre0vykMM3l3Df2DiFHViS0vplL68mXKQ7RERkjms+nXMu15ZgOBgwx/Y7kI8nfUU8CgYA5+1+mwRA1Q9ouGvc2gpELSV3tF4bl44AoUQzom7gfxQW0g4aq+0+dm3wC+fbSPii+QnlWqj4mxXShv4+/uZVhdxFmiD6pV67t7R69J1conzC65Jehgz8NgfEDc9FYcO6xyWSthr8aj5XiONM+/IL+gjMqyaH6bWES5VFLGxDyxwKBgQCMV7s5kJTWNcfe176BpgZSkd5/oJ8SElR3o5f+ZgIziR+8/XcDVpljpasTWhsgk4uO9StEutLvES62/M9HxbYwEuqm/kZMMGG4qUS+Usjefia6SXo/5lpqLnxhcaOvfTCHVnEqDYobkq2CxLCbMsSjcahBMKmd8VHSsMKxuB3JNwKBgAiiMKAkNW3jBlbzCJS28+waeSo8lnqOCzIwyUaVaUcgwvuVWsMRWQlimBcGIi4i/33qzXcR5O6fsO46Y6q0q+3TNQBLN65ZGGNnD1NofOd/xBS8ZNsK5IoMlEqeA6ms8YcB7HbwRPNZMnO7ssF9zmc1iZIYEX6JdoA1THEpUX4x',
    'alipay_public_key' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAgIb25+ZdWF1MkEVcNMIgQkDzgU9lY0xnWJezuzMuYy92VF2A3Xl3FWZ7CiTBtpFGviOe9Wsao6fA3nd/w9Kd7je75EkMPmd1HNKPHuf0m0SflDRDnwjni6wQxKBI8nyZMBEydEFYe/XR1pIqsVwBplaeoEfABYYhbGnDL42jZeLhpQw1p98B+EuKwkHpoPjTfEK/g98mxUXFBcGGbiUh4shJya9Bqkss1RwzoNos+Q4ArLmzPMxY+mFMA6tDdchWQxBhSnruRQHM2akTcLA5/DQ+g66fLkLAbGHC7+vYD2jaxA6PL4vGgcF+UyHCEXSqAGP68pp1/fPc0DMSSQv5uQIDAQAB',
),

    //微信配置
    'wechat' => array(
    'app_id'   => 'wxb9c22f6b40017548', //微信公众平台（服务号类型） APPID
    'mch_id' => '1615793028', //微信商户号
    'secret'   => 'd8c06291bdee92d91691839d32641364', //开放平台第三方平台 Secret
    'key' => '0952bb4700dbd79647c639920d26956d', //API 秘钥 (用于支付签名)
)
);
 