<?php

namespace app\admin\controller\member;

use app\admin\model\member\MemberCashOutModel;
use core\basic\Controller;

class MemberCashOutController extends Controller
{

    private $model;

    public function __construct()
    {
        $this->model = new MemberCashOutModel();
    }

    // 提现申请列表
    public function index()
    {
        if (! ! ($field = get('field', 'var')) && ! ! ($keyword = get('keyword', 'vars'))) {
            $result = $this->model->findCashOut($field, $keyword);
        } else {
            $result = $this->model->getList();
        }

        $this->assign('cashouts', $result);

        $this->display('member/cashoutlist.html');
    }

    // 提现详情
    public function view()
    {
        $result = $this->model->getCashOut(get('id', 'int'));

        $this->assign('cashout', $result);

        $this->display('member/cashoutdetail.html');
    }

    // 提现审核
    public function audit()
    {
        $id = get('id','int');
        $data['status'] = get('status','int');
        $data['audited_time'] = get_datetime();
        if ($this->model->auditCashOut($id, $data)) {
            switch ($data['status']){
                case 2:
                    $type = '通过';
                    break;
                case 3:
                    $type = '驳回';
                    break;
            }
            $this->log('ID为'.$id.'的提现申请已'.$type);
            success('提现申请已'.$type, - 1);
        } else {
            alert_back('审核失败！');
        }
    }
}