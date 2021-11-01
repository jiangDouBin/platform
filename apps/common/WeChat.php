<?php

namespace app\common;

require 'vendor/autoload.php';

use core\basic\Config;
use EasyWeChat\Factory;
class WeChat
{
    public static function getOpenPlatFormUrl(){
        $config = Config::get('wechat',true);
        $openPlatform = Factory::openPlatform($config);
        return $openPlatform->getPreAuthorizationUrl('');
    }
}
