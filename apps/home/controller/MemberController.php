<?php
/**
 * @copyright (C)2016-2099 Hnaoyun Inc.
 * @author XingMeng
 * @email hnxsh@foxmail.com
 * @date 2020年06月26日
 *  会员前台控制器
 */

namespace app\home\controller;

use app\common\Alipay;
use app\common\BasicController;
use app\common\HWCSms;
use app\common\QrCode;
use app\common\ResponseCode;
use app\common\WeChat;
use app\home\model\CashOutModel;
use app\home\model\OrderModel;
use app\home\model\TransactionModel;
use app\home\model\MemberModel;
use app\home\model\ProductModel;
use core\basic\Model;
use core\basic\Url;

class MemberController extends BasicController
{

    protected $parser;

    protected $model;

    protected $htmldir;

    public function __construct()
    {
        $this->model = new MemberModel();
        $this->parser = new ParserController();
        $this->htmldir = $this->config('tpl_html_dir') ? $this->config('tpl_html_dir') . '/' : '';
    }

    // 我的上传-上传
    public function addupload()
    {
        // 未登录时跳转到登陆页面
        if (!session('pboot_uid')) {
            location(Url::home('member/login'));
        }
        // 执行上传
        if ($_POST) {
            if (time() - session('lastreg') < 10) {
                alert_back('您上传的太频繁了，请稍后再试！');
            }
            $fileArr = post('fileArray');
            $title = post('title');
            $ext_type = post('ext_type');
            $ext_bl = post('ext_bl');
            $ext_rjl = post('ext_rjl');
            $ext_price = post('ext_price');
            $description = post('description');
            $istop = post('istop', 'int', '', '', 0);
            $isrecommend = post('isrecommend', 'int', '', '', 0);
            $isheadline = post('isheadline', 'int', '', '', 0);

            $gid = post('gid', 'int') ?: 0;
            $gtype = post('gtype', 'int') ?: 4;
            // 构建数据
            $data = array(
                'member_id' => session('pboot_uid'),
                'acode' => 'cn',
                'scode' => '5',
                'title' => $title,
                'titlecolor' => ' ',
                'subscode' => ' ',
                'status' => 0,
                'subtitle' => ' ',
                'filename' => ' ',
                'author' => session('pboot_username'),
                'source' => '上传',
                'outlink' => ' ',
                'ico' => ' ',
                'pics' => ' ',
                'content' => ' ',
                'tags' => ' ',
                'enclosure' => '',
                'keywords' => '',
                'sorting' => 255,
                'visits' => 0,
                'likes' => 0,
                'oppose' => 0,
                'create_user' => session('pboot_username'),
                'update_user' => session('pboot_username'),
                'date' => get_datetime(),
                'description' => $description
            );

            $data2 = array(
                'ext_type' => $ext_type,
                'ext_bl' => $ext_bl,
                'ext_rjl' => $ext_rjl,
                'ext_price' => $ext_price
            );
            $ProductModel = new ProductModel();
            if ($id = $ProductModel->addProduct($data)) {
                $data2['contentid'] = $id;
                if ($ProductModel->addContentExt($data2)) {
                    foreach ($fileArr as $key=>$file){
                        $fileArr[$key]['content_id'] = $id;
                        $fileArr[$key]['created_time'] = get_datetime();
                    }
                    if($ProductModel->insertUploadFiles($fileArr)){
                        session('lastreg', time()); // 记录最后提交时间
                        alert_location('上传成功，请等待管理员审核！', Url::home('member/myuploadlist'), 1);
                    }
                }
            }

            error('上传资料失败', '-1');
        }
        else{
           $orderModel = new ProductModel();
            $content = parent::parser($this->htmldir . '/upload.html');
            $data = $orderModel->getList();
            // print_r($data);
            $pagetitle = '上传';
            $content = parserList($content, $data, $pagetitle);
            // $content = str_replace('{pboot:pagetitle}','个人中心-{pboot:sitetitle}-{pboot:sitesubtitle}', $content);
            $this->cache($content, true);
        }
    }

    // 我的上传列表
    public function myuploadlist()
    {
        // 未登录时跳转到用户登录
        if (!session('pboot_uid')) {
            location(Url::home('member/login'));
        }
        $orderModel = new ProductModel();
        $content = parent::parser($this->htmldir . '/uploadlist.html');
        $data = $orderModel->getList();
        // print_r($data);
        $content = parserList($content, $data, $pagetitle);
        $this->cache($content, true);
    }
    // 下载资源
    public function xiazai() {
       // 未登录时跳转到用户登录
        if (!session('pboot_uid')) {
            location(Url::home('member/login'));
        }
        
        if($_POST) {
            $productid =  post('productid');
            $id = post('id');
            $ProductModel = new ProductModel();
            $OrderModel = new OrderModel();
            if($productinfo = $ProductModel->getContent($productid)){
                if($productinfo->status == 1) {
                    // 说明此产品已经审核通过
                    if($productinfo->member_id == session('pboot_uid')) {
                        // 直接返回地址即可  自己创建的产品可以直接下载
                        $url = $ProductModel->getxiazailist($id);
                        json(1,$url);
                    }else{
                        if($orderinfo = $OrderModel->getOrderStatus($productid)){
                            // 不是自己创建的，但是已经付款可以返回地址
                            $url = $ProductModel->getxiazailist($id);
                            json(1,$url);
                        }else {
                            error('请先对该产品进行购买');
                        }
                    }
                    
                }else {
                   // 判定当前产品是否是自己创建的
                   if($productinfo->member_id == session('pboot_uid')) {
                        // 是自己创建的可以返回地址
                        $url = $ProductModel->getxiazailist($id);
                        json(1,$url);
                   }else{
                       error('产品未审核通过或已下架');
                   }
                }
            }else {
                error('产品不存在');
            }
        }else {
            error('提交方式出错');
        }
    }
    public function login()
    {
        // 已经登录时跳转到用户中心
        if (session('pboot_uid')) {
            location(Url::home('member/ucenter'));
        }

        // 执行登录验证
        if ($_POST) {
            if ($this->config('login_status') === '0') {
                error('系统已经关闭登录功能，请到后台开启再试！');
            }

            // 验证码验证
            $checkcode = strtolower(post('checkcode', 'var'));
            if ($this->config('login_check_code') !== '0') {
                if (!$checkcode) {
                    alert_back('验证码不能为空！');
                }

                if ($checkcode != session('checkcode')) {
                    alert_back('验证码错误！');
                }
            }

            $username = post('username');
            $password = post('password');

            if (!$username) {
                alert_back('用户账号不能为空！');
            }

            // 检查用户名
            if (!$this->model->checkUsername("username='$username' or useremail='$username' or usermobile='$username'")) {
                alert_back('用户账号不存在！');
            }

            // 检查密码
            if (!$password) {
                alert_back('用户密码不能为空！');
            } else {
                $password = md5(md5($password));
            }

            // 登录验证
            if (!!$login = $this->model->login("(username='$username' or useremail='$username' or usermobile='$username') AND password='$password'")) {
                if (!$login->status) {
                    alert_back('您的账号待审核，请联系管理员！');
                }
                session('pboot_uid', $login->id);
                session('pboot_ucode', $login->ucode);
                session('pboot_username', $login->username);
                session('pboot_useremail', $login->useremail);
                session('pboot_usermobile', $login->usermobile);
                session('pboot_gid', $login->gid);
                session('pboot_gcode', $login->gcode);
                session('pboot_gname', $login->gname);
                session('pboot_wxid', $login->wxid);

                if (!!$backurl = get('backurl')) {
                    alert_location('登录成功！', $backurl, 1);
                } else {
                    alert_location('登录成功！', Url::home('member/umodify'), 1);
                }
            } else {
                alert_back('账号密码错误，请核对后重试！', 0);
            }
        } else {
            $content = parent::parser($this->htmldir . 'member/login.html'); // 框架标签解析
            $content = $this->parser->parserBefore($content); // CMS公共标签前置解析
            $content = str_replace('{pboot:pagetitle}', $this->config('login_title') ?: '会员登录-{pboot:sitetitle}-{pboot:sitesubtitle}', $content);
            $content = $this->parser->parserPositionLabel($content, 0, '会员登录', Url::home('member/login')); // CMS当前位置标签解析
            $content = $this->parser->parserSpecialPageSortLabel($content, -2, '会员登录', Url::home('member/login')); // 解析分类标签
            $content = $this->parser->parserAfter($content); // CMS公共标签后置解析
            echo $content;
            exit();
        }
    }

    // 会员注册页面
    public function register()
    {
        // 已经登录时跳转到用户中心
        if (session('pboot_uid')) {
            location(Url::home('member/umodify'));
        }

        // 执行注册
        if ($_POST) {
            if ($this->config('register_status') === '0') {
                error('系统已经关闭注册功能，请到后台开启再试！');
            }

            if (time() - session('lastreg') < 10) {
                alert_back('您注册太频繁了，请稍后再试！');
            }
            $ucode = get_auto_code($this->model->getLastUcode(), 1);
            $username = post('username'); // 接受用户名、邮箱、手机三种方式
            // $nickname = post('nickname');
            $password = post('password');
            $code = post('code');
            // $rpassword = post('rpassword');

            $usermobile = $username;
            if (!$usermobile) {
                alert_back('账号不能为空，请输入注册的手机号码！');
            }
            if (!preg_match('/^1[0-9]{10}$/', $usermobile)) {
                alert_back('账号格式不正确，请输入正确的手机号码！');
            }
            if ($this->model->checkUsername("usermobile='$usermobile' OR username='$usermobile'")) {
                alert_back('您输入的手机号码已被注册！');
            }

            if (!$password) {
                alert_back('密码不能为空！');
            } else {
                $password = md5(md5($password));
            }

            if(!$code){
                alert_back('验证码不能为空');
            }else{
                if($this->model->getSms("mobile='$usermobile' and code='$code' and status = 1")) {
                    $data = array(
                        'status' => 2
                    );
                    $this->model->modsms($data, $usermobile);
                }else{
                    alert_back('验证码错误'); 
                }
            }

            // 默认值设置
            $status = $this->config('register_verify') ? 0 : 1; // 默认不需要审核
            $score = $this->config('register_score') ?: 0;

            $group = $this->model->getFirstGroup();
            $gid = $this->model->getGroupID($this->config('register_gcode')) ?: $group->id;

            // 构建数据
            $data = array(
                'ucode' => $ucode,
                'username' => $username,
                'useremail' => $useremail,
                'usermobile' => $usermobile,
                'nickname' => $nickname,
                'password' => $password,
                'headpic' => '',
                'status' => $status,
                'gid' => $gid,
                'wxid' => '',
                'qqid' => '',
                'wbid' => '',
                'activation' => 1,
                'score' => $score,
                'register_time' => get_datetime(),
                'login_count' => 0,
                'last_login_ip' => 0,
                'last_login_time' => 0
            );

            // 读取字段
            if (!!$field = $this->model->getField()) {
                foreach ($field as $value) {
                    $field_data = post($value->name);
                    if (is_array($field_data)) { // 如果是多选等情况时转换
                        $field_data = implode(',', $field_data);
                    }
                    $field_data = preg_replace_r('pboot:if', '', $field_data);
                    if ($value->required && !$field_data) {
                        alert_back($value->description . '不能为空！');
                    } else {
                        $data[$value->name] = $field_data;
                    }
                }
            }

            // 执行注册
            if ($this->model->register($data)) {
                session('lastreg', time()); // 记录最后提交时间
                if ($status) {
                    alert_location('注册成功！', Url::home('member/login'), 1);
                } else {
                    alert_location('注册成功，请等待管理员审核！', Url::home('member/login'), 1);
                }
            } else {
                error('会员注册失败！', -1);
            }
        } else {
            $content = parent::parser($this->htmldir . 'member/register.html'); // 框架标签解析
            $content = $this->parser->parserBefore($content); // CMS公共标签前置解析
            $content = str_replace('{pboot:pagetitle}', $this->config('register_title') ?: '会员注册-{pboot:sitetitle}-{pboot:sitesubtitle}', $content);
            $content = $this->parser->parserPositionLabel($content, 0, '会员注册', Url::home('member/register')); // CMS当前位置标签解析
            $content = $this->parser->parserSpecialPageSortLabel($content, -3, '会员注册', Url::home('member/register')); // 解析分类标签
            $content = $this->parser->parserAfter($content); // CMS公共标签后置解析
            echo $content;
            exit();
        }
    }

    // 用户中心页面
    public function ucenter()
    {
        // 未登录时跳转到用户登录
        if (!session('pboot_uid')) {
            location(Url::home('member/login'));
        }

        $content = parent::parser($this->htmldir . 'member/ucenter.html'); // 框架标签解析
        $content = $this->parser->parserBefore($content); // CMS公共标签前置解析
        $content = str_replace('{pboot:pagetitle}', $this->config('ucenter_title') ?: '个人中心-{pboot:sitetitle}-{pboot:sitesubtitle}', $content);
        $content = $this->parser->parserPositionLabel($content, 0, '个人中心', Url::home('member/ucenter')); // CMS当前位置标签解析
        $content = $this->parser->parserSpecialPageSortLabel($content, -4, '个人中心', Url::home('member/ucenter')); // 解析分类标签
        $content = $this->parser->parserAfter($content); // CMS公共标签后置解析
        echo $content;
        exit();
    }

    // 用户修改页面以及执行修改接口
    public function umodify()
    {
        // 未登录时跳转到用户登录
        if (!session('pboot_uid')) {
            location(Url::home('member/login'));
        }

        // 执行资料修改
        if ($_POST && session('pboot_uid')) {
            $nickname = post('nickname');
            $useremail = post('useremail');
            $usermobile = post('usermobile');
            $usercompany = post('company');
            $userbank = post('bank');
            $userbankcard = post('bank_card');
            $userrealname = post('realname');
            $userwxid = post('wxid');
            $userzfbid = post('zfbid');
            $password = post('password');
            $rpassword = post('rpassword');
            $headpic = str_replace(SITE_DIR, '', post('headpic'));

            if ($useremail) { // 邮箱校验
                if (!preg_match('/^[\w]+@[\w\.]+\.[a-zA-Z]+$/', $useremail)) {
                    alert_back('邮箱格式不正确，请输入正确的邮箱账号！');
                }
                if ($this->model->checkUsername("(useremail='$useremail' OR username='$useremail') AND id<>'" . session('pboot_uid') . "'")) {
                    alert_back('您输入的邮箱已被注册！');
                }
            }

            if ($usermobile) { // 手机检验
                if (!preg_match('/^1[0-9]{10}$/', $usermobile)) {
                    alert_back('手机格式不正确，请输入正确的手机号码！');
                }
                if ($this->model->checkUsername("(usermobile='$usermobile' OR username='$usermobile') AND id<>'" . session('pboot_uid') . "'")) {
                    alert_back('您输入的手机号码已被注册！');
                }
            }

            // 构建数据
            $data = array(
                'nickname' => $nickname,
                'useremail' => $useremail,
                'usermobile' => $usermobile,
                'headpic' => $headpic,
                'company' => $usercompany,
                'bank' => $userbank,
                'bank_card' => $userbankcard,
                'realname' => $userrealname,
                'wxid' => $userwxid,
                'zfbid' => $userzfbid
            );

            // 密码修改
            if ($password) {
                if ($password != $rpassword) {
                    alert_back('确认密码不正确！');
                } else {
                    $data['password'] = md5(md5($password));
                }
            }

            // 读取字段
            if (!!$field = $this->model->getField()) {
                foreach ($field as $value) {
                    $field_data = post($value->name);
                    if (is_array($field_data)) { // 如果是多选等情况时转换
                        $field_data = implode(',', $field_data);
                    }
                    $field_data = preg_replace_r('pboot:if', '', $field_data);
                    if ($value->required && !$field_data) {
                        alert_back($value->description . '不能为空！');
                    } else {
                        $data[$value->name] = $field_data;
                    }
                }
            }

            // 不允许修改的字段
            unset($data['id']);
            unset($data['ucode']);
            unset($data['username']);
            unset($data['status']);
            unset($data['gid']);
            unset($data['wxid']);
            unset($data['qqid']);
            unset($data['wbid']);
            unset($data['score']);
            unset($data['register_time']);
            unset($data['login_count']);
            unset($data['last_login_ip']);
            unset($data['last_login_time']);

            // 执行修改
            if ($this->model->modUser($data)) {
                alert_location('修改成功！', Url::home('member/umodify'), 1);
            } else {
                error('资料修改失败！', -1);
            }
        } else {
            $content = parent::parser($this->htmldir . 'member/umodify.html'); // 框架标签解析
            $content = $this->parser->parserBefore($content); // CMS公共标签前置解析
            $content = str_replace('{pboot:pagetitle}', $this->config('umodify_title') ?: '资料修改-{pboot:sitetitle}-{pboot:sitesubtitle}', $content);
            $content = $this->parser->parserPositionLabel($content, 0, '资料修改', Url::home('member/umodify')); // CMS当前位置标签解析
            $content = $this->parser->parserSpecialPageSortLabel($content, -5, '资料修改', Url::home('member/umodify')); // 解析分类标签
            $content = $this->parser->parserAfter($content); // CMS公共标签后置解析
            echo $content;
            exit();
        }
    }

    // 退出登录
    public function logout()
    {
        session('pboot_uid', '');
        session('pboot_ucode', '');
        session('pboot_username', '');
        session('pboot_useremail', '');
        session('pboot_usermobile', '');
        session('pboot_gid', '');
        session('pboot_gcode', '');
        session('pboot_gname', '');
        location(Url::home('member/login'));
    }

    // 文件上传方法(Ajax)
    public function upload()
    {
        // 必须登录
        if (!session('pboot_uid')) {
            json(0, '请先登录！');
        }

        $ext = $this->config('home_upload_ext') ?: "jpg,jpeg,png,gif,xls,xlsx,doc,docx,ppt,pptx,rar,zip,pdf,txt,dwg";
        $upload = upload('upload', $ext);
        if (is_array($upload)) {
            json(1, $upload);
        } else {
            json(0, $upload);
        }
    }

    // 发送邮件
    public function sendEmail()
    {
        if ($this->config('register_check_code') != 2) {
            json(0, '发送失败，后台配置非邮箱验证码模式！');
        }

        if (time() - session('lastsend') < 10) {
            json(0, '您提交太频繁了，请稍后再试！');
        }

        if (!session('sendemail')) {
            json(0, '非法提交发送邮件！');
        }

        // 发送邮箱参数
        if (!!$to = post('to')) {
            if (!preg_match('/^[\w]+@[\w]+\.[a-zA-Z]+$/', $to)) {
                json(0, '邮箱格式不正确，请输入正确的邮箱账号！');
            }
        } else {
            json(0, '发送失败，缺少发送对象参数to！');
        }

        // 检查邮箱注册
        if ($this->model->checkUsername("useremail='$to' OR username='$to'")) {
            alert_back('您输入的邮箱已被注册！');
        }

        $rs = false;
        if ($to) {
            session('lastsend', time()); // 记录最后提交时间
            $mail_subject = "【PbootCMS】您有新的验证码信息，请注意查收！";
            $code = create_code(4);
            session('checkcode', strtolower($code));
            $mail_body = "您的验证码为：" . $code;
            $mail_body .= '<br>来自网站 ' . get_http_url() . ' （' . date('Y-m-d H:i:s') . '）';
            $rs = sendmail($this->config(), $to, $mail_subject, $mail_body);
        }
        if ($rs === true) {
            json(1, '发送成功！');
        } else {
            json(0, '发送失败，' . $rs);
        }
    }

    // 订单详情
    public function orderinfo() {
        if (! session('pboot_uid')) {
            location(Url::home('member/login'));
        }
        $orderid = $_GET['id'];
        $orderModel = new orderModel();
        if($result=$orderModel->getOrder($orderid)) {
            // $pagetitle = '下单支付';
            // $content = parent::parser($this->htmldir . '/pay.html');
            // print_r($result);
            $this->assign('order',$result);
            $this->displayFile('html/pay.html');
            // $content = parserList($content,$result,$pagetitle);
            // $this->cache($content, true);
        }else {
            error('订单失败error');
        }
    }
    // 检查用户是否注册
    public function isRegister()
    {
        // 接受用户名、邮箱、手机三种方式
        // $username = post('username');
        $usermobile = post('username');

        // 注册类型判断
        if ($this->config('register_type') == 3) { // 手机注册
            if (!preg_match('/^1[0-9]{10}$/', $usermobile)) {
                $err = '账号格式不正确，请输入正确的手机号码！';
            }
            if ($this->model->checkUsername("usermobile='$usermobile'")) {
                $err = '您输入的手机号码已被注册！';
            } else {
                $suc = '您输入的手机号码可以使用！';
            }
        }

        if ($err) {
            json(1, $err);
        } else {
            json(0, $suc);
        }
    }

    public function _empty()
    {
        _404('您访问的地址不存在，请核对再试！');
    }

    // 获取用户钱包信息
    private function getAmountInfo()
    {
        $transactionModel = new TransactionModel();
        $incomeAmount = round($transactionModel->getIncomeAmount(TransactionModel::TYPE_INCOME), 2);
        $lastOne = $transactionModel->getLastOne(session('pboot_uid'));
        $member = $this->model->getUser();

        if ($lastOne)
            $ktx_amount = $lastOne->current_balance > $member->balance ? $member->balance : $lastOne->current_balance;
        else
            $ktx_amount = $member->balance;

        $cashOutModel = new CashOutModel();
        $sqzAmount = round($cashOutModel->getApplyAmount(), 2); //申请中的提现总额
        $ktx_amount = $ktx_amount - $sqzAmount;
        $ktx_amount = $ktx_amount >= 0 ? $ktx_amount : 0;
        $ytxAmount = round($cashOutModel->getOutAmount(), 2);

        return array($incomeAmount, $ktx_amount, $ytxAmount);
    }

    // 消费记录
    public function orders()
    {
        $orderModel = new OrderModel();
        $content = parent::parser($this->htmldir . '/download.html');
        $pagetitle = "消费记录"; // 页面标题
        $data = $orderModel->getOrders();
        foreach ($data as $key => $order) {
            switch ($order->payment_type) {
                case 1:
                    $order->payment_type = '余额';
                    break;
                case 2:
                    $order->payment_type = '微信';
                    break;
                case 3:
                    $order->payment_type = '支付宝';
                    break;
                default:
                    $order->payment_type = '未支付';
                    $order->payment_time = '未支付';
                    break;
            }
            $data[$key] = $order;
        }
        $content = parserList($content, $data, $pagetitle);
        $this->cache($content, true);
    }

    //提现记录
    public function cashout()
    {
        // 未登录时跳转到用户登录
        if (!session('pboot_uid')) {
            location(Url::home('member/login'));
        }

        list($incomeAmount, $ktx_amount, $ytxAmount) = $this->getAmountInfo();

        $this->pageTitle = "提现记录"; // 页面标题
        $this->pageKeywords = "提现记录"; // 页面关键字
        $this->pageDescription = "提现记录"; // 页面说明
        $this->pageUrl = 'home/mycashout';
        $this->pageBread = '我的收益';

        $this->assign('income_amount', $incomeAmount);
        $this->assign('ktx_amount', $ktx_amount);
        $this->assign('ytx_amount', $ytxAmount);

        $cashOutModel = new CashOutModel();
        $data = $cashOutModel->getCashouts();
        $this->assign('cashouts', $data);
        $this->displayFile('html/member/mycashout.html');
    }

    // 我的下载
    public function mydownlaod()
    {
        $orderModel = new OrderModel();
        // 未登录时跳转到用户登录
        if (!session('pboot_uid')) {
            location(Url::home('member/login'));
        }
        $content = parent::parser('html/downloadlist.html');
        $data = $orderModel->getdownloads();

        $content = parserList($content, $data, $pagetitle);
        // $content = parserList($content,$data,$pagetitle);
        $this->cache($content, true);
        // $this->displayFile('html/downloadlist.html');
    }

    //申请提现
    public function applyCashOut()
    {
        // 未登录时跳转到用户登录
        if (!session('pboot_uid')) {
            location(Url::home('member/login'));
        }

        if ($_POST && session('pboot_uid')) {
            list(, $ktx_amount,) = $this->getAmountInfo();

            if (post('amount') > $ktx_amount)
                error('可提现金额不足！', -1);

            $data['member_id'] = session('pboot_uid');
            $data['amount'] = post('amount');
            $data['created_time'] = get_datetime();

            $model = new CashOutModel();
            $result = $model->insert($data);

            if ($result)
                alert_location('提现申请提交成功！', Url::home('member/cashout'), 1);
        }
        error('提现申请提交失败！', -1);
    }

    public function sendSms(){
        $mobile = post('username');
        if(empty($mobile)){
            error('请输入手机号',-1);
            return;
        }
        $chars='0123456789';
        $yzm='';
        while(strlen($yzm)<6)
           $yzm.=substr($chars,(mt_rand()%strlen($chars)),1);
        $mmm = $yzm;
        $yzm = '"'.$yzm.'"';
        // 构建数据
        $data = array(
            'mobile' => $mobile,
            'code' => $mmm,
            'time' => date('Y-m-d h:i:s', time())
        );
        if($this->model->sendSms($data)){
            HWCSms::SendSms([$mobile], $yzm);
            return responseJson(true,'成功',['url' => '成功']);
        }else{
            error('短信存储失败',-1);
        }   
    }

    //微信网页授权登录
    public function wechatLogin(){
        $url = WeChat::getWeChatLoginQRCodeUrl();
        return responseJson(true,'成功',['url' => $url]);
    }

    // 绑定支付宝或者微信页面
    public function bind(){
        $this->pageTitle = "账号绑定"; // 页面标题
        $this->pageKeywords = "账号绑定"; // 页面关键字
        $this->pageDescription = "账号绑定"; // 页面说明
        $this->pageUrl = 'member/bind';
        $this->pageBread = '账号绑定';
        $this->displayFile('html/member/bind.html');
    }

    //执行绑定操作
    public function doBind(){
        $wxid = session('pboot_wxid');
        $zfbid = session('pboot_zfbid');
        $nickname = session('pboot_nick_name');
        $headpic = session('pboot_avatar');
        $usermobile = $_POST['usermobile'];
        $password = $_POST['password'];
        $code = $_POST['code'];
        if(empty($usermobile)) {
            alert_back('请输入手机号');
        }else if(empty($password)) {
            alert_back('请输入密码');
        }else if(empty($code)){
            alert_back('请输入验证码');
        }
        
        $ucode = get_auto_code($this->model->getLastUcode(), 1);
        
        $status = $this->config('register_verify') ? 0 : 1; // 默认不需要审核
        $score = $this->config('register_score') ?: 0;
        $group = $this->model->getFirstGroup();
        $gid = $this->model->getGroupID($this->config('register_gcode')) ?: $group->id;
        
        if(!$this->model->getSms("mobile='$usermobile' and code='$code' and status = 1")) {
            alert_back('验证码错误'); 
        }
        

        $model = $this->model->login("wxid='$wxid' or usermobile='$usermobile'");

        if($model){
            if(!empty($model->wxid)){
                error('账号已绑定，请勿重复！', -1);
            }else{
                $mima = md5(md5($password));
                $modelss = $this->model->login("password='$mima' and usermobile='$usermobile'");
                if(!$modelss){
                    error('密码输入错误', -1);
                    return;
                }
                $data = array(
                    'status' => 2
                );
                $this->model->modsms($data, $usermobile);
                $data=array(
                    'headpic' => $headpic,
                    'wxid' => $wxid
                );
                if ($this->model->modWechat($data, $model->id)) {
                    session('pboot_uid', $model->id);
                    session('pboot_ucode', $model->ucode);
                    session('pboot_username', $model->username);
                    session('pboot_useremail', $model->useremail);
                    session('pboot_usermobile', $model->usermobile);
                    session('pboot_gid', $model->gid);
                    session('pboot_gcode', $model->gcode);
                    session('pboot_gname', $model->gname);
                   return responseJson(true,'成功',['url' => '/member/umodify']);
                } else {
                    error('绑定修改失败！', -1);
                }
            }
        }else{
            // 构建数据
        $data = array(
            'ucode' => $ucode,
            'username' =>  $usermobile,
            'useremail' => '',
            'usermobile' => $usermobile,
            'nickname' => $nickname,
            'password' => '',
            'headpic' => $headpic,
            'status' => $status,
            'gid' => $gid,
            'wxid' => $wxid,
            'zfbid' => $zfbid,
            'qqid' => '',
            'wbid' => '',
            'activation' => 1,
            'score' => $score,
            'register_time' => get_datetime(),
            'login_count' => 0,
            'last_login_ip' => 0,
            'last_login_time' => 0
        );

        // 执行注册
        if ($this->model->register($data)) {
            session('lastreg', time()); // 记录最后提交时间
            // 登录验证
            if ($login = $this->model->login("usermobile='$usermobile'")) {
                $data = array(
                    'status' => 2
                );
                $this->model->modsms($data, $usermobile);
                session('pboot_uid', $login->id);
                session('pboot_ucode', $login->ucode);
                session('pboot_username', $login->username);
                session('pboot_useremail', $login->useremail);
                session('pboot_usermobile', $login->usermobile);
                session('pboot_gid', $login->gid);
                session('pboot_gcode', $login->gcode);
                session('pboot_gname', $login->gname);

                return responseJson(true,'成功',['url' => '/member/umodify']);
            }
        }

        error('绑定失败！', -1);
        }
        
    }
}