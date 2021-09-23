<?php
/**
 * @author NDHT
 * @email songping_tiger@163.com
 * @date  2021年9月23日
 *  会员订单模型
 */
namespace app\home\model;

use core\basic\Model;
use core\basic\Config;

class OrderModel extends Model
{

    // 会员登录
    public function login($where)
    {
        $field = array(
            'a.id',
            'a.ucode',
            'a.username',
            'a.useremail',
            'a.usermobile',
            'a.gid',
            'a.status',
            'b.gcode',
            'b.gname'
        );
        $join = array(
            'ay_member_group b',
            'a.gid=b.id',
            'LEFT'
        );
        if (! ! $user = parent::table('ay_member a')->field($field)
            ->join($join)
            ->where($where)
            ->find()) {
            $data = array(
                'login_count' => '+=1',
                'last_login_ip' => ip2long(get_user_ip()),
                'last_login_time' => get_datetime()
            );
            // 登录积分
            $score = Config::get('login_score') ?: 0;
            if (is_numeric($score) && $score > 0) {
                $data['score'] = '+=' . $score;
            }
            // 更新登录信息
            parent::table('ay_member')->where('id=' . $user->id)->update($data);
        }
        return $user;
    }

    // 会员注册
    public function register($data)
    {
        return parent::table('ay_member')->insert($data);
    }

    // 检查会员名称
    public function checkUsername($where)
    {
        return parent::table('ay_member')->where($where)->find();
    }

    // 获取当前会员信息
    public function getUser()
    {
        $field = array(
            'a.*',
            'b.gcode',
            'b.gname'
        );
        $join = array(
            'ay_member_group b',
            'a.gid=b.id',
            'LEFT'
        );
        return parent::table('ay_member a')->field($field)
            ->join($join)
            ->where("a.id='" . session('pboot_uid') . "'")
            ->find();
    }

    // 修改会员资料
    public function modUser($data)
    {
        return parent::table('ay_member')->where("id='" . session('pboot_uid') . "'")->update($data);
    }

    // 获取第一个等级
    public function getFirstGroup()
    {
        return parent::table('ay_member_group')->order('gcode asc')->find();
    }

    // 获取消费记录
    public function getOrders($start,$num){
        // 筛选条件支持模糊匹配
        return parent::table('ay_orders a')->field(['a.*'])
            ->where("member_id='" . session('pboot_uid') . "'")
            ->order('a.id DESC')
            ->limit($start - 1, $num)
            ->decode()
            ->select();
    }
}