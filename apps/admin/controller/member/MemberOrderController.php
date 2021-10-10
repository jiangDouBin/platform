<?php

namespace app\admin\controller\member;

use app\admin\model\member\MemberOrderModel;
use core\basic\Controller;

class MemberOrderController extends Controller
{

    private $model;

    public function __construct()
    {
        $this->model = new MemberOrderModel();
    }

    // 订单列表
    public function index()
    {
        if (! ! ($field = get('field', 'var')) && ! ! ($keyword = get('keyword', 'vars'))) {
            $result = $this->model->findOrder($field, $keyword);
        } else {
            $result = $this->model->getList();
        }

        $this->assign('orders', $result);

        $this->display('member/orderlist.html');
    }

    // 订单详情
    public function view()
    {
        $result = $this->model->getOrder(get('id', 'int'));

        $this->assign('order', $result);

        $this->display('member/orderdetail.html');
    }

    // 文章评论删除
    public function del()
    {
        // 执行批量删除
        if ($_POST) {
            if (! ! $list = post('list')) {
                if ($this->model->delCommentList($list)) {
                    $this->log('批量删除评论成功！');
                    success('删除成功！', - 1);
                } else {
                    $this->log('批量删除评论失败！');
                    error('删除失败！', - 1);
                }
            } else {
                alert_back('请选择要删除的评论！');
            }
        }
        
        if (! $id = get('id', 'int')) {
            error('传递的参数值错误！', - 1);
        }
        
        if ($this->model->delComment($id)) {
            $this->log('删除文章评论' . $id . '成功！');
            success('删除成功！', - 1);
        } else {
            $this->log('删除文章评论' . $id . '失败！');
            error('删除失败！', - 1);
        }
    }

    // 字段修改
    public function mod()
    {
        if (! ! $submit = post('submit')) {
            switch ($submit) {
                case 'verify1':
                    $list = post('list');
                    if (! $list) {
                        alert_back('请选择要操作的评论！');
                    }
                    if ($this->model->modCommentList($list, "status=1")) {
                        $this->log('评论批量审核成功！');
                        success('审核成功！', - 1);
                    } else {
                        alert_back('审核失败！');
                    }
                    break;
                case 'verify0':
                    $list = post('list');
                    if (! $list) {
                        alert_back('请选择要操作的评论！');
                    }
                    if ($this->model->modCommentList($list, "status=0")) {
                        $this->log('评论批量禁用成功！');
                        success('禁用成功！', - 1);
                    } else {
                        alert_back('禁用失败！');
                    }
                    break;
            }
        }
        
        if (! $id = get('id', 'int')) {
            error('传递的参数值错误！', - 1);
        }
        
        // 单独修改状态
        if (($field = get('field', 'var')) && ! is_null($value = get('value', 'var'))) {
            if ($this->model->modComment($id, "$field='$value',update_user='" . session('username') . "'")) {
                location(- 1);
            } else {
                alert_back('修改失败！');
            }
        }
    }
}