<?php
/**
 * @copyright (C)2016-2099 Hnaoyun Inc.
 * @author XingMeng
 * @email hnxsh@foxmail.com
 * @date 2016年11月6日
 *  应用控制基类  
 */
namespace app\common;

use app\home\controller\ParserController;
use core\basic\Config;
use core\basic\Controller;
use core\basic\Url;
use core\view\View;
use core\view\Paging;

class BasicController extends Controller
{
    protected $pageTitle = '';
    protected $pageKeywords = '';
    protected $pageDescription = '';
    protected $pageUrl = '';
    protected $pageBread = '';


    // 显示模板
    final protected function displayFile($file)
    {
        $view = View::getInstance();
        $content = $view->parser($file);
        $parser = new ParserController();
        $content = $parser->parserBefore($content); // CMS公共标签前置解析
        $content = str_replace('{pboot:pagetitle}', ($this->pageTitle . '-{pboot:sitetitle}-{pboot:sitesubtitle}'), $content);
        $content = str_replace('{pboot:pagekeywords}', $this->pageKeywords, $content);
        $content = str_replace('{pboot:pagedescription}', $this->pageDescription, $content);
        $content = $this->parser->parserPositionLabel($content, 0, $this->pageBread, Url::home($this->pageUrl)); // CMS当前位置标签解析
        $content = $this->parser->parserSpecialPageSortLabel($content, - 4, $this->pageBread, Url::home($this->pageUrl)); // 解析分类标签
        $content = $parser->parserAfter($content); // CMS公共标签后置解析
        $content = $this->runtime($content);
        echo $this->gzip($content);
        exit();
    }

    // 解析运行时间标签
    private function runtime($content)
    {
        return str_replace('{pboot:runtime}', 'Processed in ' . round(microtime(true) - START_TIME, 6) . ' second(s).', $content);
    }

    // 压缩内容
    private function gzip($content)
    {
        if (Config::get('gzip') && ! headers_sent() && extension_loaded("zlib") && strstr($_SERVER["HTTP_ACCEPT_ENCODING"], "gzip")) {
            $content = gzencode($content, 6);
            header("Content-Encoding: gzip");
            header("Vary: Accept-Encoding");
            header("Content-Length: " . strlen($content));
        }
        return $content;
    }
}

