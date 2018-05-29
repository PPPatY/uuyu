<?php
/**
 * Created by PhpStorm.
 * User: xmy 280564871@qq.com
 * Date: 2017/4/1
 * Time: 15:41
 */

namespace Common\Model;
use Common\Model\PointRecordModel;

class PointTypeModel extends TabModel {

	/**
	 * 列表
	 * @param string $map
	 * @param string $order
	 * @param int $p
	 * @return mixed
	 * author: xmy 280564871@qq.com
	 */
	public function getLists($map="",$order="create_time desc",$p=1,$row=10){
		$page = intval($p);
		$page = $page ? $page : 1; //默认显示第一页数据
		$data['data'] = $this->where($map)->order($order)->page($page,$row)->select();
		$data['count'] = $this->where($map)->count();
		return $data;
	}
	public function getUserLists($map="",$join='',$order="ctime desc",$p=1,$row=10){

		$page = intval($p);
		$page = $page ? $page : 1; //默认显示第一页数据
		$data = $this->alias('pt')
					  ->field('pt.*,pr.user_id,pr.day,pr.create_time as ctime,date_format(FROM_UNIXTIME(pr.create_time),"%Y-%m-%d") as ct')
		              ->join('tab_point_record as pr on pr.type_id = pt.id'.$join,'left')
		              ->where($map)
		              ->order($order)
		              ->page($page,$row)
		              ->select();
		return $data;
	}
	/**
	 * 编辑
	 * @param $id
	 * @return bool
	 * author: xmy 280564871@qq.com
	 */
	public function edit($id){
		$map['id'] = $id;
		$data = $this->create();
		if(!$data){
			return false;
		}
		$result = $this->where($map)->save($data);
		return $result;
	}

	public function userGetPoint($user,$type){
		$record = new PointRecordModel();
		$record->startTrans();
		switch (strtolower($type)) {
			case 'sign_in':
				$map['key'] = 'sign_in';
				break;
			case 'bind_phone':
				$map['key'] = 'bind_phone';
				break;
		}
		$detail = $this->alias('pt')
					 ->where($map)
					 ->find();
		if(empty($detail['status'])){
			return -1;//没有此类型或关闭
		}
		switch (strtolower($type)) {
			case 'sign_in'://签到
				$signrecord = $record->field('date_format(FROM_UNIXTIME(create_time),"%Y-%m-%d") as timeday,day')->where(['type_id'=>$detail['id'],'user_id'=>$user])->order('create_time desc')->find();
				$today = date("Y-m-d",time());
				$yesterday = date("Y-m-d",strtotime("-1 day"));

				$redata['type_id'] 	= $detail['id'];
				$redata['user_id'] 	= $user;
				$redata['point'] 	= $detail['point'];
				$redata['type'] 	= 1;
				if($signrecord['timeday']==$today){
					return -2;//已签到
				}elseif($signrecord['timeday']!=$yesterday){
					$redata['day'] 	= 1;
				}elseif($signrecord['day']>=7){
					$redata['day'] = 1;
				}else{
					$redata['day'] 	= $signrecord['day']+1;
				}
				$addpoint = $detail['point']+($detail['time_of_day']*($redata['day']-1));
				$redata['point'] = $addpoint;
				$savedata = $record->create($redata);
				$addsignin= $record->add($savedata);
				if($addsignin!=true){
					$record->rollback();
					return 0;//签到失败
				}
				$addpoint = M('User','tab_')->where(['id'=>$user])->setInc('shop_point',$addpoint);
				if($addpoint){
					$record->commit();
					return 1;
				}else{
					$record->rollback();
					return 0;//签到失败
				}
				break;
			case 'bind_phone'://首次绑定手机
				$bindphone = $record->field('id')->where(['type_id'=>$detail['id'],'user_id'=>$user])->order('create_time desc')->find();
				if(!empty($bindphone)){
					return -100 ;//已绑定过
				}else{
					$redata['type_id'] 	= $detail['id'];
					$redata['user_id'] 	= $user;
					$redata['point'] 	= $detail['point'];
					$redata['type'] 	= 1;
					$savedata = $record->create($redata);
					$addsignin= $record->add($savedata);
					if($addsignin!=true){
						$record->rollback();
						return -200;//绑定失败
					}
					$addpoint = M('User','tab_')->where(['id'=>$user])->setInc('shop_point',$detail['point']);
					if($addpoint){
						$record->commit();
						return 1;
					}else{
						$record->rollback();
						return -200;//绑定失败
					}
				}
				break;
		}
	}
}