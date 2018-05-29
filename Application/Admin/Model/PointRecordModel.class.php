<?php
/**
 * Created by PhpStorm.
 * User: xmy 280564871@qq.com
 * Date: 2017/4/1
 * Time: 15:41
 */

namespace Admin\Model;
use Admin\Model\PointShopRecordModel;
class PointRecordModel extends TabModel {

	protected $_auto = [
		['create_time','time',self::MODEL_BOTH,'function'],
	];


	public function getLists($map="",$order="pr.create_time desc",$p=1,$row=10){
		$page = intval($p);
		$page = $page ? $page : 1; //默认显示第一页数据
		$data['data'] = $this
			->table("tab_point_record as pr")
			->field("pr.*,date_format(FROM_UNIXTIME(pr.create_time),'%Y-%m-%d') as ct,u.account,pt.name,pt.key")
			->where($map)
			->join("tab_user u on u.id=pr.user_id")
			->join("tab_point_type pt on pr.type_id = pt.id")
			->order($order)
			->page($page,$row)
			->select();
		$data['count'] = $this
			->table("tab_point_record as pr")
			->where($map)
			->join("tab_user u on u.id=pr.user_id")
			->join("tab_point_type pt on pr.type_id = pt.id")
			->count();
		return $data;
	}


	public function getRecordLists($map="",$order="pr.create_time desc,pr.id desc",$p=1,$row=1000){
		$page = intval($p);
		$page = $page ? $page : 1; //默认显示第一页数据
		$data = $this
			->table("tab_point_record as pr")
			->field("pr.*,date_format(FROM_UNIXTIME(pr.create_time),'%Y-%m-%d') as ct,u.account,pt.name,pt.key")
			->where($map)
			->join("tab_user u on u.id=pr.user_id")
			->join("tab_point_type pt on pr.type_id = pt.id")
			->order($order)
			->page($page,$row)
			->select();
		$rr['pointrecord'] = $data;
		//兑换记录
		$srmap['user_id'] = $map['user_id'];
		$model = new PointShopRecordModel();
		$data1 = $model->getRecordLists($srmap,'sr.create_time desc,sr.id desc',1,1000);
		foreach ($data1 as $key => $value) {
			$data1[$key]['cover'] = icon_url($value['detail_cover']);
			$value['cover'] = icon_url($value['detail_cover']);
			$data[] = $value;
		}
		$res = my_sort($data,'create_time',SORT_DESC,SORT_NUMERIC);
		$rr['pointshoprecord'] = $data1;
		$rr['all'] = $res;
		return $rr;
	}



    public function getRecordLists2($map="",$order="pr.create_time desc,pr.id desc",$p=1,$row=1000){
        $page = intval($p);
        $page = $page ? $page : 1; //默认显示第一页数据
        $data = $this
            ->table("tab_point_record as pr")
            ->field("pr.*,date_format(FROM_UNIXTIME(pr.create_time),'%Y-%m-%d') as ct,u.account,pt.name,pt.key")
            ->where($map)
            ->join("tab_user u on u.id=pr.user_id")
            ->join("tab_point_type pt on pr.type_id = pt.id")
            ->order($order)
            ->page($page,$row)
            ->select();
        foreach ($data as $k => $v){
            $data[$k]['ct'] = time_format($data[$k]['create_time'],'Y-m-d');
            $data[$k]['good_name'] = $data[$k]['name'];
            $data[$k]['pay_amount'] = $data[$k]['point'];
        }
        $rr['pointrecord'] = $data;
        //兑换记录
        $srmap['user_id'] = $map['user_id'];
        $model = new PointShopRecordModel();
        $data1 = $model->getRecordLists($srmap,'sr.create_time desc,sr.id desc',1,1000);

        foreach ($data1 as $key => $value) {
            $data1[$key]['good_key'] = json_decode($data1[$key]['good_key'],true)[0];
            $data1[$key]['cover'] = icon_url($value['detail_cover']);
            $value['cover'] = icon_url($value['detail_cover']);
            $data[] = $value;
        }
        $res = my_sort($data,'create_time',SORT_DESC,SORT_NUMERIC);
        $rr['pointshoprecord'] = $data1;
        $rr['all'] = $res;
        return $rr;
    }
}