<?php
/**
 * Created by PhpStorm.
 * User: xmy 280564871@qq.com
 * Date: 2017/4/1
 * Time: 10:16
 */

namespace Common\Model;

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
			->field("sr.user_id,sr.good_name,sr.good_id,sr.number,ifnull(sr.pay_amount,0) as pay_amount,sr.create_time,ifnull(sr.address,'') as address,u.nickname,u.account")
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
	public function getRecordLists($map,$order="",$p=1,$row=10){
		$page = intval($p);
		$page = $page ? $page : 1; //默认显示第一页数据
		$data = $this
			->table("tab_point_shop_record as sr")
			->field("sr.user_id,sr.good_name,sr.good_id,sr.number,sr.pay_amount,sr.good_type,sr.good_key,sr.create_time,ifnull(sr.address,'') as address,sr.user_name as real_name,sr.phone,u.nickname,p.cover,p.detail_cover")
			->join("left join tab_user u on u.id = sr.user_id")
			->join("left join tab_point_shop p on p.id = sr.good_id")
			->where($map)
			->order($order)
			->page($page, $row)
			->select();
		foreach ($data as $key => $value) {
			if($value['good_type']==2){
				$good_key = json_decode($value['good_key'],true);
				$data[$key]['goodmark'] = $good_key[0];
			}else{
				$str = '';
				if($value['real_name']){
					$str .= $value['real_name'].',';
				}
				if($value['phone']){
					$str .= $value['phone'].',';
				}
				$data[$key]['goodmark'] = $str.$value['address'];
			}
		}
		return $data;
	}
}