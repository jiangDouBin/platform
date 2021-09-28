<?php
/**
 * @author NDHT
 * @email songping_tiger@163.com
 * @date  2021年9月23日
 *  会员订单模型
 */
namespace app\home\model;

use core\basic\Model;

class TransactionModel extends Model
{
    const TYPE_INCOME = 1;
    const TYPE_EXPENSE = 2;

    // 获取交易记录
    public function getTransactions($type = 0, $action = 0){
        // 筛选条件支持模糊匹配
        $model = parent::table('ay_transactions a')
            ->field(['a.*'])
            ->where('member_id='.session('pboot_uid'));

        if($type)
            $model->where('type='.$type);
        if($action)
            $model->where('action='.$action);

        return $model->order('a.id DESC')
            ->page()
            ->select();
    }

    // 获取收入总额
    public function getIncomeAmount($type){
        // 筛选条件支持模糊匹配
        return parent::table('ay_transactions a')
            ->field(['a.*'])
            ->where('member_id='.session('pboot_uid'))
            ->where('type='.$type)
            ->sum(action_amount);
    }

    public function getLastOne(){
        return parent::table('ay_transactions a')
            ->where('member_id='.session('pboot_uid'))
            ->order('id DESC')
            ->limit(1)
            ->find();
    }
}