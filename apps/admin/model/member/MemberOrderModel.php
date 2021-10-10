<?php

namespace app\admin\model\member;

use core\basic\Model;

class MemberOrderModel extends Model
{

    // 获取列表
    public function getList()
    {
        $field = array(
            'a.*',
            'b.title',
            'c.username',
            'c.nickname',
            'c.headpic'
        );
        $join = array(
            array(
                'ay_content b',
                'a.product_id=b.id',
                'LEFT'
            ),
            array(
                'ay_member c',
                'a.member_id=c.id',
                'LEFT'
            )
        );
        return parent::table('ay_orders a')->field($field)
            ->join($join)
            ->order('a.id desc')
            ->page()
            ->select();
    }

    // 查找
    public function findOrder($field, $keyword)
    {
        $fields = array(
            'a.*',
            'b.title',
            'c.username',
            'c.nickname',
            'c.headpic'
        );
        $join = array(
            array(
                'ay_content b',
                'a.product_id=b.id',
                'LEFT'
            ),
            array(
                'ay_member c',
                'a.member_id=c.id',
                'LEFT'
            )
        );
        return parent::table('ay_orders a')->field($fields)
            ->join($join)
            ->like($field, $keyword)
            ->order('a.id desc')
            ->page()
            ->select();
    }

    // 获取详情
    public function getOrder($id)
    {
        $field = array(
            'a.*',
            'b.title',
            'c.username',
            'c.nickname',
            'c.headpic'
        );
        $join = array(
            array(
                'ay_content b',
                'a.product_id=b.id',
                'LEFT'
            ),
            array(
                'ay_member c',
                'a.member_id=c.id',
                'LEFT'
            )
        );
        
        return parent::table('ay_orders a')->field($field)
            ->join($join)
            ->where("a.id=$id")
            ->find();
    }

    // 删除
    public function delComment($id)
    {
        return parent::table('ay_member_comment')->where("id=$id")->delete();
    }

    // 删除多个
    public function delCommentList($ids)
    {
        return parent::table('ay_member_comment')->delete($ids);
    }

    // 修改
    public function modComment($id, $data)
    {
        return parent::table('ay_member_comment')->where("id=$id")
            ->autoTime()
            ->update($data);
    }

    // 修改多个
    public function modCommentList($ids, $data)
    {
        return parent::table('ay_member_comment')->in('id', $ids)->update($data);
    }
}