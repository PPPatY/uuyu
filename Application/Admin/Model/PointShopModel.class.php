<?php
/**
 * Created by PhpStorm.
 * User: xmy 280564871@qq.com
 * Date: 2017/4/5
 * Time: 21:00
 */

namespace Admin\Model;
use Common\Model\UserModel;
class PointShopModel extends TabModel{

	protected $_validate = [
		['good_name', 'require', '商品名称不能为空', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH],
		['price', 'require', '商品价格不能为空', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH],
		['price', 'number', '商品价格错误', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH],
		['number', 'number', '商品数量错误', self::VALUE_VALIDATE , 'regex', self::MODEL_BOTH],
		// ['good_info', 'require', '商品信息不能为空', self::MUST_VALIDATE , 'regex', self::MODEL_INSERT],
		['good_type', 'require', '商品类型不能为空', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH],
	];

	protected $_auto = [
		['good_key', 'formatStr', self::MODEL_BOTH,'callback'],
		['create_time', 'time', self::MODEL_INSERT,'function'],
	];


	/**
	 * 获取列表
	 * @param string $map
	 * @param string $order
	 * @param int $p
	 * @return mixed
	 * author: xmy 280564871@qq.com
	 */
	public function getLists($map = "", $order = "create_time desc", $p = 1,$row=10)
	{
		$page = intval($p);
		$page = $page ? $page : 1; //默认显示第一页数据
		if(strtolower(MODULE_NAME)=='admin'){

			$data['data'] = $this
				->where($map)
				->order($order)
				->page($page, $row)
				->select();
		}else{//}elseif(strtolower(ACTION_NAME)=='mall'){
			$data['data'] = $this
				->field('good_key',true)
				->where($map)
				->order($order)
				->page($page, $row)
				->select();
			foreach ($data['data'] as $key => $value) {
				$data['data'][$key]['cover'] = icon_url($value['cover']);
			}
		}
		$data['count'] = $this->where($map)->count();
		return $data;
	}


	/**
	 * 数据保存
	 * @param string $id
	 * @return bool|mixed
	 * author: xmy 280564871@qq.com
	 */
	public function saveData($id=""){
		$data = $this->create();
		if(!$data){
			return false;
		}
		//计算激活码数量
		if($data['good_type'] == 2){
			$data['number'] = $this->countJson($data['good_key']);
		}
		if(empty($id)){
			return $this->add($data);
		}else{
			return $this->where(['id'=>$id])->save($data);
		}
	}

	/**
	 * 数据格式化
	 * @param $str
	 * @return array|string
	 * author: xmy 280564871@qq.com
	 */
	public function formatStr($str){
		if (empty($str)){
			return $str;
		}
		$data = str2arr($str,"\r\n");
		$data = array_filter($data);//去空
		$result = json_encode($data);
		return $result;
	}


	/**
	 * 获取数据
	 * @param $id
	 * @return mixed
	 * author: xmy 280564871@qq.com
	 */
	public function getData($id){
		$data = $this->find($id);
		$good_key = json_decode($data['good_key']);
		$data['good_key'] = arr2str($good_key,"\r\n");
		return $data;
	}


	public function countJson($str){
		$good_key = json_decode($str);
		$num = count($good_key);
		return $num;
	}

	public function goodsDetail($id,$user_id='',$map,$isbr=false){
		$map['ps.status'] = 1;
		$map['ps.id'] = $id;
		if($user_id<=0){
			$data = $this->alias('ps')
					->where($map)
					->find();
		}else{
			$data = $this->alias('ps')
					->field('ps.*,u.shop_point,u.nickname,u.shop_address')
					->join('tab_user u on u.id='.$user_id)
					->where($map)
					->find();
		}

		unset($data['good_key']);
		if(!empty($data)) {
			$data['cover'] = icon_url($data['cover']);
			$data['detail_cover'] = icon_url($data['detail_cover']);
            if ($isbr){
                $data['good_info'] = str_replace(array("\r\n", "\r", "\n"),'<br />',$data['good_info']);
            }
		}
		return $data;
	}
	protected function goods_goodkey($good_id){
        $map['ps.status'] = 1;
        $map['ps.id'] = $good_id;
        $data = $this->alias('ps')
            ->where($map)
            ->find();
        return $data;
    }
    public function shopBuy($user,$good_id,$num){
        $detail = $this->goods_goodkey($good_id);
        $price = $detail['price'];
        $total = $price*$num;


        $userpoint = M()->table('tab_user')->where(['id'=>$user])->field('shop_point,account,shop_address')->find();

        if($userpoint['shop_point']-$detail['price']<0){
            return -3;
        }

        /*$decgood = M()->table('tab_point_shop')->where(['id'=>$good_id])->setDec('number',$num);
        if($decgood==false){
            $shiwu->rollback();
            return 0;
        }*/

        $good_num = M()->table('tab_point_shop')->where(['id'=>$good_id])->getField('number');
        if($good_num-$num<0){
            return -4;
        }
        if($detail['good_type']==2&&$num>1){
            return 0;
        }

        $shiwu = M();
        $shiwu->startTrans();

        //判断完各种验证条件之后  执行pointshop表的字段变更  和 pointshoprecord表的字段增加
        if ($detail['good_type'] == 2){

            //领取兑换码
            $good_key = json_decode($detail['good_key']);
            $i = $num;
            while ($i > 0){
                $key[] = array_shift($good_key);
                $i--;
            }
            $good['id'] = $good_id;
            $good['good_key'] = json_encode($good_key);
            $good['number'] = $detail['number']-$num;

            $data['good_key'] = json_encode($key);

            $data['good_id'] 	= $detail['id'];
            $data['good_name'] 	= $detail['good_name'];
            $data['good_type'] 	= $detail['good_type'];
//		$data['good_key'] 	= $detail['good_key'];
            $data['pay_amount'] 	= $total;
            $data['number'] 	= $num;

            $shopaddress = json_decode($userpoint['shop_address'],true);
            $data['user_id'] 	= $user;
            $data['user_name'] 	= $shopaddress['consignee'];
            $data['address'] 	= $shopaddress['consignee_address'];
            $data['phone'] 		= $shopaddress['consignee_phone'];
            $data['create_time']= time();
            $data['status'] 	=1;

            $res = M()->table('tab_point_shop_record')->add($data);
            $res2 = M()->table('tab_point_shop')->save($good);
            $dec = M()->table('tab_user')->where(['id'=>$user])->setDec('shop_point',$total);

            if($res!==false && $res2 !== false && $dec !== false){
                $shiwu->commit();
                if(strtolower(MODULE_NAME)!='app'){
                	return array('status'=>1,'key'=>$key,'good_type'=>$detail['good_type']);
                }else{
                	return 1;
                }
                // return $key;
            }else{
                $shiwu->rollback();
                return 0;
            }
        }else{
            //实物商品不存在
            $good['id'] = $good_id;
            $good['number'] = $detail['number']-$num;

            $data['good_id'] 	= $detail['id'];
            $data['good_name'] 	= $detail['good_name'];
            $data['good_type'] 	= $detail['good_type'];
//		$data['good_key'] 	= $detail['good_key'];
            $data['pay_amount'] 	= $total;
            $data['number'] 	= $num;
            $shopaddress = json_decode($userpoint['shop_address'],true);
            $data['user_id'] 	= $user;
            $data['user_name'] 	= $shopaddress['consignee'];
            $data['address'] 	= $shopaddress['consignee_address'];
            $data['phone'] 		= $shopaddress['consignee_phone'];
            $data['create_time']= time();
            $data['status'] 	= 0;

            $res = M()->table('tab_point_shop_record')->add($data);
            $res2 = M()->table('tab_point_shop')->save($good);
            $dec = M()->table('tab_user')->where(['id'=>$user])->setDec('shop_point',$total);
            if($res!==false && $res2 !== false && $dec !== false){
                $shiwu->commit();
                return 1;
            }else{
                $shiwu->rollback();
                return 0;
            }
        }
    }
}