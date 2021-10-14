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

class ProductModel extends Model
{
    public function getList()
    {
        $field = array(
            'a.*',
            'b.*'
        );
        $join = array(
            array(
                'ay_content_ext b',
                'a.id=b.contentid',
                'LEFT'
            )
        );
        return parent::table('ay_content a')->field($field)
            ->where("a.acode='cn'")
            ->where("a.scode='5'")
            ->where("a.member_id='" . 1 . "'")
            ->join($join)
            ->order('a.sorting ASC,a.id DESC')
            ->page()
            ->select();
    }

    public function addProduct($data)
    {
        return parent::table('ay_content')->autoTime()->insertGetId($data);
    }

    public function addContentExt(array $data)
    {
        return parent::table('ay_content_ext')->insert($data);
    }

    // 获取文章详情
    public function getContent($id)
    {
        $field = array(
            'a.*',
            'b.*',
        );
        $join = array(
            array(
                'ay_content_ext b',
                'a.id=b.contentid',
                'LEFT'
            )
        );
        return parent::table('ay_content a')->field($field)
            ->where("a.id=$id")
            ->where("a.acode='" . session('acode') . "'")
            ->join($join)
            ->find();
    }

    //添加产品资料
    public function insertUploadFiles($data){
        return parent::table('ay_content_upload')->insert($data);
    }
}