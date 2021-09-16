<?php
/**
 * @copyright (C)2016-2099 Hnaoyun Inc.
 * @author XingMeng
 * @email hnxsh@foxmail.com
 * @date 2017年12月15日
 *  列表产品下载模型类
 */
namespace app\admin\model\content;

use core\basic\Model;

class ContentUploadModel extends Model
{

    protected $scodes = array();

    // 获取产品下载列表
    public function getList($mcode)
    {
        $field = array(
            'a.id',
            'a.name',
            'a.scode',
            'c.name as subsortname',
            'a.subscode',
            'a.title',
            'a.subtitle',
            'a.date',
            'a.sorting',
            'a.status',
            'a.istop',
            'a.isrecommend',
            'a.isheadline',
            'a.visits',
            'a.ico',
            'a.pics',
            'a.filename',
            'a.outlink',
            'd.urlname',
            'b.filename as sortfilename'
        );
        $join = array(
            array(
                'ay_content_sort b',
                'a.scode=b.scode',
                'LEFT'
            ),
            array(
                'ay_content_sort c',
                'a.subscode=c.scode',
                'LEFT'
            ),
            array(
                'ay_model d',
                'b.mcode=d.mcode',
                'LEFT'
            )
        );
        return parent::table('ay_content a')->field($field)
            ->where("b.mcode='$mcode'")
            ->where('d.type=2 OR d.type is null ')
            ->where("a.acode='" . session('acode') . "'")
            ->join($join)
            ->order('a.sorting ASC,a.id DESC')
            ->page()
            ->select();
    }

    // 查找指定分类及子类产品下载
    public function findContent($mcode, $scode, $keyword)
    {
        $fields = array(
            'a.id',
            'b.name as sortname',
            'a.scode',
            'c.name as subsortname',
            'a.subscode',
            'a.title',
            'a.subtitle',
            'a.date',
            'a.sorting',
            'a.status',
            'a.istop',
            'a.isrecommend',
            'a.isheadline',
            'a.visits',
            'a.ico',
            'a.pics',
            'a.filename',
            'a.outlink',
            'd.urlname',
            'b.filename as sortfilename'
        );
        $join = array(
            array(
                'ay_content_sort b',
                'a.scode=b.scode',
                'LEFT'
            ),
            array(
                'ay_content_sort c',
                'a.subscode=c.scode',
                'LEFT'
            ),
            array(
                'ay_model d',
                'b.mcode=d.mcode',
                'LEFT'
            )
        );
        $this->scodes = array(); // 先清空
        $scodes = $this->getSubScodes($scode);
        return parent::table('ay_content a')->field($fields)
            ->where("b.mcode='$mcode'")
            ->where('d.type=2 OR d.type is null ')
            ->where("a.acode='" . session('acode') . "'")
            ->in('a.scode', $scodes)
            ->like('a.title', $keyword)
            ->join($join)
            ->order('a.sorting ASC,a.id DESC')
            ->page()
            ->select();
    }

    // 在全部栏目查找产品下载
    public function findContentAll($mcode, $keyword)
    {
        $fields = array(
            'a.id',
            'b.name as sortname',
            'a.scode',
            'c.name as subsortname',
            'a.subscode',
            'a.title',
            'a.subtitle',
            'a.date',
            'a.sorting',
            'a.status',
            'a.istop',
            'a.isrecommend',
            'a.isheadline',
            'a.visits',
            'a.ico',
            'a.pics',
            'a.filename',
            'a.outlink',
            'd.urlname',
            'b.filename as sortfilename'
        );
        $join = array(
            array(
                'ay_content_sort b',
                'a.scode=b.scode',
                'LEFT'
            ),
            array(
                'ay_content_sort c',
                'a.subscode=c.scode',
                'LEFT'
            ),
            array(
                'ay_model d',
                'b.mcode=d.mcode',
                'LEFT'
            )
        );
        return parent::table('ay_content a')->field($fields)
            ->where("b.mcode='$mcode'")
            ->where('d.type=2 OR d.type is null ')
            ->where("a.acode='" . session('acode') . "'")
            ->like('a.title', $keyword)
            ->join($join)
            ->order('a.sorting ASC,a.id DESC')
            ->page()
            ->select();
    }

    // 获取子栏目
    public function getSubScodes($scode)
    {
        if (! $scode) {
            return;
        }
        $this->scodes[] = $scode;
        $subs = parent::table('ay_content_sort')->where("pcode='$scode'")->column('scode');
        if ($subs) {
            foreach ($subs as $value) {
                $this->getSubScodes($value);
            }
        }
        return $this->scodes;
    }

    // 检查产品下载
    public function checkContent($where)
    {
        return parent::table('ay_content')->field('id')
            ->where($where)
            ->find();
    }

    // 获取产品下载详情
    public function getContent($id)
    {
        $field = array(
            'id',
            'name',
            'created_time'
        );
        
        return parent::table('ay_content_upload')->field($field)
            ->where("content_id=$id")
            ->order('id DESC')
            ->select();
    }

    // 添加产品下载
    public function addContent(array $data)
    {
        return parent::table('ay_content')->autoTime()->insertGetId($data);
    }

    // 删除产品下载
    public function delContent($id)
    {
        return parent::table('ay_content')->where("id=$id")
            ->where("acode='" . session('acode') . "'")
            ->delete();
    }

    // 删除产品下载
    public function delContentList($ids)
    {
        return parent::table('ay_content')->where("acode='" . session('acode') . "'")->delete($ids);
    }

    // 修改产品下载
    public function modContent($id, $data)
    {
        return parent::table('ay_content')->autoTime()
            ->in('id', $id)
            ->where("acode='" . session('acode') . "'")
            ->update($data);
    }

    // 复制内容到指定栏目
    public function copyContent($ids, $scode)
    {
        // 查找出要复制的主内容
        $data = parent::table('ay_content')->in('id', $ids)->select(1);
        
        foreach ($data as $key => $value) {
            // 查找扩展内容
            $extdata = parent::table('ay_content_ext')->where('contentid=' . $value['id'])->find(1);
            
            // 去除主键并修改栏目
            unset($value['id']);
            $value['scode'] = $scode;
            
            // 插入主内容
            $id = parent::table('ay_content')->insertGetId($value);
            
            // 插入扩展内容
            if ($id && $extdata) {
                unset($extdata['extid']);
                $extdata['contentid'] = $id;
                $result = parent::table('ay_content_ext')->insert($extdata);
            } else {
                $result = $id;
            }
        }
        return $result;
    }

    // 查找产品下载扩展内容
    public function findContentExt($id)
    {
        return parent::table('ay_content_ext')->where("contentid=$id")->find();
    }

    // 添加产品下载扩展内容
    public function addContentExt(array $data)
    {
        return parent::table('ay_content_ext')->insert($data);
    }

    // 修改产品下载扩展内容
    public function modContentExt($id, $data)
    {
        return parent::table('ay_content_ext')->where("contentid=$id")->update($data);
    }

    // 删除产品下载扩展内容
    public function delContentExt($id)
    {
        return parent::table('ay_content_ext')->where("contentid=$id")->delete();
    }

}