<?php
/**
 * @ copyright (C)2016-2099 Hnaoyun Inc.
 * @ author XingMeng
 * @ email hnxsh@foxmail.com
 * @ date 2017年4月14日
 * 公共处理函数
 */
use core\basic\Config;
use core\basic\Model;
use app\home\controller\ParserController;

// 获取字符串型自动编码
function get_auto_code($string, $start = '1')
{
    if (! $string)
        return $start;
    if (is_numeric($string)) { // 如果纯数字则直接加1
        return sprintf('%0' . strlen($string) . 's', $string + 1);
    } else { // 非纯数字则先分拆
        $reg = '/^([a-zA-Z-_]+)([0-9]+)$/';
        $str = preg_replace($reg, '$1', $string); // 字母部分
        $num = preg_replace($reg, '$2', $string); // 数字部分
        return $str . sprintf('%0' . (strlen($string) - strlen($str)) . 's', $num + 1);
    }
}

// 获取指定分类列表
function get_type($tcode)
{
    $type_model = model('admin.system.Type');
    if (! ! $result = $type_model->getItem($tcode)) {
        return $result;
    } else {
        return array();
    }
}

// 生成区域选择
function make_area_Select($tree, $selectid = null)
{
    $list_html = '';
    global $blank;
    foreach ($tree as $values) {
        // 默认选择项
        if ($selectid == $values->acode) {
            $select = "selected='selected'";
        } else {
            $select = '';
        }
        
        // 禁用父栏目选择功能
        if ($values->son) {
            $disabled = "disabled='disabled'";
        } else {
            $disabled = '';
        }
        $list_html .= "<option value='{$values->acode}' $select $disabled>{$blank}{$values->acode} {$values->name}";
        
        // 子菜单处理
        if ($values->son) {
            $blank .= '　　';
            $list_html .= make_area_Select($values->son, $selectid);
        }
    }
    // 循环完后回归位置
    $blank = substr($blank, 0, - 6);
    return $list_html;
}

// 检测指定的方法是否拥有权限
function check_level($btnAction, $isPath = false)
{
    $user_level = session('levels');
    if ($isPath) {
        if (in_array($btnAction, $user_level)) {
            return true;
        }
    } else {
        if (in_array('/' . M . '/' . C . '/' . $btnAction, $user_level) || session('id') == 1) {
            return true;
        }
    }
}

// 获取返回按钮
function get_btn_back($btnName = '返 回')
{
    if (! ! $backurl = get('backurl')) {
        $url = base64_decode($backurl);
    } elseif (isset($_SERVER["HTTP_REFERER"])) {
        $url = $_SERVER["HTTP_REFERER"];
    } else {
        $url = url('/' . M . '/' . C . '/index');
    }
    
    $btn_html = "<a href='" . $url . "' class='layui-btn layui-btn-primary'>$btnName</a>";
    return $btn_html;
}

// 获取新增按钮
function get_btn_add($btnName = '新 增')
{
    $user_level = session('levels');
    if (! in_array('/' . M . '/' . C . '/add', $user_level) && session('id') != 1)
        return;
    $btn_html = "<a href='" . url("/" . M . '/' . C . "/add") . get_btn_qs() . "' class='layui-btn layui-btn-primary'>$btnName</a>";
    return $btn_html;
}

// 获取更多按钮
function get_btn_more($idValue, $id = 'id', $btnName = '详情')
{
    $btn_html = "<a href='" . url("/" . M . '/' . C . "/index/$id/$idValue") . "' class='layui-btn layui-btn-xs layui-btn-primary' title='$btnName'>$btnName</a>";
    return $btn_html;
}

// 获取删除按钮
function get_btn_del($idValue, $id = 'id', $btnName = '删除')
{
    $user_level = session('levels');
    if (! in_array('/' . M . '/' . C . '/del', $user_level) && session('id') != 1)
        return;
    $btn_html = "<a href='" . url('/' . M . '/' . C . "/del/$id/$idValue") . "' onclick='return confirm(\"您确定要删除么？\")' class='layui-btn layui-btn-xs layui-btn-danger' title='$btnName'>$btnName</a>";
    return $btn_html;
}

// 获取修改按钮
function get_btn_mod($idValue, $id = 'id', $btnName = '修改')
{
    $user_level = session('levels');
    if (! in_array('/' . M . '/' . C . '/mod', $user_level) && session('id') != 1)
        return;
    $btn_html = "<a href='" . url("/" . M . '/' . C . "/mod/$id/$idValue") . get_btn_qs() . "'  class='layui-btn layui-btn-xs'>$btnName</a>";
    return $btn_html;
}

// 获取其它按钮
function get_btn($btnName, $theme, $btnAction, $idValue, $id = 'id')
{
    $user_level = session('levels');
    if (! in_array('/' . M . '/' . C . '/' . $btnAction, $user_level) && session('id') != 1)
        return;
    $btn_html = "<a href='" . url("/" . M . '/' . C . "/$btnAction/$id/$idValue") . get_btn_qs() . "'  class='layui-btn layui-btn-xs $theme'>$btnName</a>";
    return $btn_html;
}

// 获取按钮返回参数
function get_btn_qs()
{
    if (isset($_SERVER["QUERY_STRING"]) && ! ! $qs = $_SERVER["QUERY_STRING"]) {
        return "&backurl=" . base64_encode(URL);
    } else {
        return "?backurl=" . base64_encode(URL);
    }
}

// 获取返回URL
function get_backurl()
{
    if (! ! $backurl = get('backurl')) {
        if (isset($_SERVER["QUERY_STRING"]) && ! ! get('p')) {
            return "&backurl=" . $backurl;
        } else {
            return "?backurl=" . $backurl;
        }
    } else {
        return;
    }
}

// 获取返回tab跳转地址
function get_tab($tid)
{
    if (isset($_SERVER["QUERY_STRING"]) && ! ! get('p')) {
        return "&#tab=" . $tid;
    } else {
        return "?#tab=" . $tid;
    }
}

// 缓存语言信息
function cache_config($refresh = false)
{
    // 系统配置缓存
    $config_cache = RUN_PATH . '/config/' . md5('config') . '.php';
    if (! file_exists($config_cache) || $refresh) {
        $model = model('admin.system.Config');
        Config::set(md5('config'), $model->getConfig(), false, true);
    }
    
    // 多语言缓存
    $lg_cache = RUN_PATH . '/config/' . md5('area') . '.php';
    if (! file_exists($lg_cache) || $refresh) {
        $model = model('admin.system.Config');
        $area = $model->getAreaTheme(); // 获取所有语言
        $map = array();
        foreach ($area as $key => $value) {
            $map[$value['acode']] = $value;
        }
        if (! $map) {
            error('系统没有任何可用区域，请核对后再试！');
        }
        $lgs['lgs'] = $map;
        Config::set(md5('area'), $lgs, false, true);
    }
}

// 获取默认语言
function get_default_lg()
{
    $default = current(Config::get('lgs'));
    return $default['acode'];
}

// 获取当前语言并进行安全处理
function get_lg()
{
    $lg = cookie('lg');
    if (! $lg || ! preg_match('/^[\w\-]+$/', $lg)) {
        $lg = get_default_lg();
        cookie('lg', $lg);
    }
    return $lg;
}

// 获取当前语言主题
function get_theme()
{
    $lgs = Config::get('lgs');
    $lg = get_lg();
    return $lgs[$lg]['theme'];
}

// 推送百度
function post_baidu($api, $urls)
{
    $ch = curl_init();
    $options = array(
        CURLOPT_CONNECTTIMEOUT => 30,
        CURLOPT_TIMEOUT => 90,
        CURLOPT_URL => $api,
        CURLOPT_POST => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POSTFIELDS => implode("\n", $urls),
        CURLOPT_HTTPHEADER => array(
            'Content-Type: text/plain'
        )
    );
    curl_setopt_array($ch, $options);
    $result = json_decode(curl_exec($ch));
    return $result;
}

//列表解析
function parserList($content,$data,$pagetitle=''){
    $parser = new ParserController();
    $content = $parser->parserBefore($content); // CMS公共标签前置解析
    $content = str_replace('{pboot:pagetitle}', ($pagetitle . '-{pboot:sitetitle}-{pboot:sitesubtitle}'), $content);
    $content = str_replace('{pboot:pagekeywords}', '', $content);
    $content = str_replace('{pboot:pagedescription}', '', $content);
    $pattern = '/\{pboot:list(\s+[^}]+)?\}([\s\S]*?)\{\/pboot:list\}/';
    $pattern2 = '/\[list:([\w\+\-\*\/\%]+)(\s+[^]]+)?\]/';
    if (preg_match_all($pattern, $content, $matches)) {
        $count = count($matches[0]);
        for ($i = 0; $i < $count; $i ++) {
            // 获取调节参数
            $params = parserParam($matches[1][$i]);
            $num = Config::get('pagesize'); // 未设置条数时使用默认15
            $start = 1; // 起始条数，默认第一条开始

            // 分离参数
            foreach ($params as $key => $value) {
                switch ($key) {
                    case 'num':
                        $num = $value;
                        break;
                    case 'start':
                        $start = $value;
                        break;
                }
            }

            // 起始数校验
            if (! is_numeric($start) || $start < 1) {
                $start = 1;
            }

            // 无数据直接替换
            if (! $data) {
                $content = str_replace($matches[0][$i], '', $content);
                continue;
            }
            // 匹配到内部标签
            if (preg_match_all($pattern2, $matches[2][$i], $matches2)) {
                $count2 = count($matches2[0]); // 循环内的内容标签数量
            } else {
                $count2 = 0;
            }

            $out_html = '';
            $pagenum = defined('PAGE') ? PAGE : 1;
            $key = ($pagenum - 1) * $num + 1;
            foreach ($data as $value) { // 按查询数据条数循环
                $one_html = $matches[2][$i];
                for ($j = 0; $j < $count2; $j ++) { // 循环替换数据
                    $one_html = str_replace($matches2[0][$j], $value->{$matches2[1][$j]}, $one_html);
                }
                $key ++;
                $out_html .= $one_html;
            }
            $content = str_replace($matches[0][$i], $out_html, $content);
        }
    }
    $content = $parser->parserAfter($content); // CMS公共标签后置解析
    return $content;
}

// 解析调节参数
function parserParam($string, $striptags = true)
{
    if (! $string = trim($string))
        return array();
    $string = preg_replace('/\s+/', ' ', $string); // 多空格处理
    if ($striptags) {
        $string = strip_tags($string);
    }
    $param = array();
    if (preg_match_all('/([\w]+)[\s]?=[\s]?([\'\"]([^\'\"]+)?[\'\"]|([^\s]+))/i', $string, $matches)) {
        foreach ($matches[1] as $key => $value) {
            if ($matches[3][$key]) {
                $param[$value] = $matches[3][$key];
            } else {
                $param[$value] = $matches[4][$key];
            }
        }
    }
    return $param;
}



