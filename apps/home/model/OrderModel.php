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
    // 获取消费记录
    public function getOrders(){
        // 筛选条件支持模糊匹配
        return parent::table('ay_orders a')->field(['a.*'])
            ->where("member_id='" . session('pboot_uid') . "'")
            ->order('a.id DESC')
            ->page()
            ->select();
    }

    // 获取我的下载列表
    public function getdownloads() {
        $field = array(
            'a.product_id as id',
            'b.*',
            'c.*'
        );
        $join = array(
            array(
                'ay_content b',
                'a.product_id=b.id',
                'LEFT'
            ),
            array(
                'ay_content_ext c',
                'a.product_id=c.contentid',
                'LEFT'
            )
        );
        return parent::table('ay_orders a')->field($field)
            ->join($join)
            ->where("a.member_id='" . session('pboot_uid') . "' and a.status = 2")
            ->page()
            ->select();
    }
}