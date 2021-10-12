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
    public function addProduct($data) {
        return parent::table('ay_content')->autoTime()->insertGetId($data);
    }

    public function addContentExt(array $data)
    {
        return parent::table('ay_content_ext')->insert($data);
    }
}