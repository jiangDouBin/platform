<?php
/**
 * @author NDHT
 * @email songping_tiger@163.com
 * @date  2021年9月23日
 *  会员订单模型
 */
namespace app\home\model;

use core\basic\Model;

class CashOutModel extends Model
{
    public $table = 'ay_cashouts';
    const STATUS_SQZ = 1;
    const STATUS_YDZ = 2;
    const STATUS_YC = 3;

    // 获取提现记录
    public function getCashouts(){
        // 筛选条件支持模糊匹配
        return parent::table('ay_cashouts a')->field(['a.*'])
            ->where("member_id='" . session('pboot_uid') . "'")
            ->order('a.id DESC')
            ->page()
            ->select();
    }

    // 已提现总额
    public function getOutAmount(){
        return parent::table('ay_cashouts a')
            ->field(['a.*'])
            ->where('member_id='.session('pboot_uid'))
            ->where('status='.self::STATUS_YDZ)
            ->sum(amount);
    }

    // 申请中的提现总额
    public function getApplyAmount(){
        return parent::table('ay_cashouts a')
            ->field(['a.*'])
            ->where('member_id='.session('pboot_uid'))
            ->where('status='.self::STATUS_SQZ)
            ->sum(amount);
    }
}