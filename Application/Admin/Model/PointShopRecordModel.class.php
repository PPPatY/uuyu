<?php
/**
 * Created by PhpStorm.
 * User: xmy 280564871@qq.com
 * Date: 2017/4/1
 * Time: 10:16
 */

namespace Admin\Model;

class PointShopRecordModel extends TabModel{



	/**
	 * 列表
	 * @param $map
	 * @param string $order
	 * @param int $p
	 * @return mixed
	 * author: xmy 280564871@qq.com
	 */
	public function getLists($map,$order="",$p=1,$row=10){
		$page = intval($p);
		$page = $page ? $page : 1; //默认显示第一页数据
		$data['data'] = $this
			->table("tab_point_shop_record as sr")
			->field("sr.id,sr.user_id,sr.good_name,sr.good_id,sr.number,sr.pay_amount,sr.create_time,sr.good_type,sr.address,sr.user_name,sr.phone,sr.status,sr.good_key,u.nickname,u.account")
			->join("left join tab_user u on u.id = sr.user_id")
			->where($map)
			->order($order)
			->page($page, $row)
			->select();
		if(strtolower(ACTION_NAME)!='mall'){

			$data['count'] = $this
				->table("tab_point_shop_record as sr")
				->where($map)
				->join("left join tab_user u on u.id = sr.user_id")
				->count();
		}
		return $data;
	}
	public function getRecordLists($map,$order="sr.create_time desc,sr.id desc",$p=1,$row=10){
		$page = intval($p);
		$page = $page ? $page : 1; //默认显示第一页数据
		$data = $this
			->table("tab_point_shop_record as sr")
			->field("sr.user_id,sr.good_name,sr.good_id,sr.number,sr.pay_amount,sr.create_time,sr.good_type,sr.good_key,sr.address,sr.user_name as real_name,sr.phone,u.nickname,p.cover,p.detail_cover")
			->join("left join tab_user u on u.id = sr.user_id")
			->join("left join tab_point_shop p on p.id = sr.good_id")
			->where($map)
			->order($order)
			->page($page, $row)
			->select();
		return $data;
	}

	public function getCountRecordLists($map,$order="sr.create_time desc,sr.id desc",$p=1,$row=10){
		$page = intval($p);
		$page = $page ? $page : 1; //默认显示第一页数据
		$data = $this
			->table("tab_point_shop_record as sr")
			->field("count(sr.good_id) as num,sr.user_id,sr.good_name,sr.good_id,p.number,sr.pay_amount,sr.create_time,sr.good_type,sr.good_key,sr.address,sr.user_name as real_name,sr.phone,p.cover,p.detail_cover")
			->join("left join tab_point_shop p on p.id = sr.good_id")
			->where($map)
			->order($order)
			->page($page, $row)
			->group('sr.good_id')
			->select();
		return $data;
	}
}