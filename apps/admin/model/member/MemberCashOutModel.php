<?php

namespace app\admin\model\member;

use core\basic\Db;
use core\basic\Model;
use core\database\Mysqli;

class MemberCashOutModel extends Model
{

    // 获取列表
    public function getList()
    {
        $field = array(
            'a.*',
            'c.username',
            'c.nickname',
            'c.headpic'
        );
        $join =
            array(
                'ay_member c',
                'a.member_id=c.id',
                'LEFT'
            );
        return parent::table('ay_cashouts a')->field($field)
            ->join($join)
            ->order('a.id desc')
            ->page()
            ->select();
    }

    // 查找
    public function findCashOut($field, $keyword)
    {
        $fields = array(
            'a.*',
            'c.username',
            'c.nickname',
            'c.headpic'
        );
        $join =
            array(
                'ay_member c',
                'a.member_id=c.id',
                'LEFT'
            );
        return parent::table('ay_cashouts a')->field($fields)
            ->join($join)
            ->like($field, $keyword)
            ->order('a.id desc')
            ->page()
            ->select();
    }

    // 获取详情
    public function getCashOut($id)
    {
        $field = array(
            'a.*',
            'c.username',
            'c.nickname',
            'c.headpic'
        );
        $join =
            array(
                'ay_member c',
                'a.member_id=c.id',
                'LEFT'
            );
        
        return parent::table('ay_cashouts a')->field($field)
            ->join($join)
            ->where("a.id=$id")
            ->find();
    }

    // 提现审核
    public function auditCashOut($id, $data)
    {
        // 需要添加事务
        $cashout = parent::table('ay_cashouts')->where("id=$id")->find();
        $member = parent::table('ay_member')->where("id=$cashout->member_id")->find();
        //$this->begin();
        parent::table('ay_cashouts')->where("id=$id")
            ->update($data);
        $insert['member_id'] = $cashout->member_id; // 交易涉及账号
        $insert['type'] = 2; // 支出
        $insert['action'] = 3; // 提现
        $insert['action_amount'] = $cashout->maction_amount;
        $insert['operator_id'] = $cashout->mmember_id;
        $insert['created_time'] = get_datetime();
        parent::table('ay_transactions')->insert($insert);
        //$this->commit();
        return true;
    }
}