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
    // 获取交易记录
    public function getTransactions($action){
        // 筛选条件支持模糊匹配
        return parent::table('ay_transactions a')->field(['a.*'])
            ->where("member_id='" . session('pboot_uid') . "'")
            ->where('action='.$action)
            ->order('a.id DESC')
            ->page()
            ->select();
    }
}