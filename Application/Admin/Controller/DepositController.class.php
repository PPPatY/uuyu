<?php

namespace Admin\Controller;
use User\Api\UserApi as UserApi;

/**
 * 后台首页控制器
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
class DepositController extends ThinkController {
    const model_name = 'Deposit';

    public function lists(){
        if(isset($_REQUEST['user_account'])){
            $map['user_account']=array('like','%'.trim($_REQUEST['user_account']).'%');
            unset($_REQUEST['user_account']);
        }
        if(isset($_REQUEST['pay_order_number'])){
            $map['pay_order_number']=array('like','%'.trim($_REQUEST['pay_order_number']).'%');
            unset($_REQUEST['pay_order_number']);
        }
        if(isset($_REQUEST['pay_ip'])){
            $map['pay_ip']=array('like','%'.trim($_REQUEST['pay_ip']).'%');
            unset($_REQUEST['pay_ip']);
        }
        if(!isset($_REQUEST['promote_id'])){

        }else if(isset($_REQUEST['promote_id']) && $_REQUEST['promote_id']==0){
            $map['promote_id']=array('elt',0);
            unset($_REQUEST['promote_id']);
            unset($_REQUEST['promote_name']);
        }elseif(isset($_REQUEST['promote_name'])&&$_REQUEST['promote_id']==-1){
            $map['promote_id']=get_promote_id($_REQUEST['promote_name']);
            unset($_REQUEST['promote_id']);
            unset($_REQUEST['promote_name']);
        }else{
            $map['promote_id']=$_REQUEST['promote_id'];
            unset($_REQUEST['promote_id']);
            unset($_REQUEST['promote_name']);
        }

				
				if (!empty($_REQUEST['timestart']) && !empty($_REQUEST['timeend'])) {
					$map['create_time'] = array('BETWEEN',array(strtotime($_REQUEST['timestart']),strtotime($_REQUEST['timeend'])+24*60*60-1));
					
				} elseif (!empty($_REQUEST['timestart']) ) {
					$map['create_time'] = array('BETWEEN',array(strtotime($_REQUEST['timestart']),time()));
					
				} elseif (!empty($_REQUEST['timeend']) ) {
					$map['create_time'] = array('elt',strtotime($_REQUEST['timeend'])+24*60*60-1);
					
				}
				
				
        if(isset($_REQUEST['start'])&&isset($_REQUEST['end'])){
            $map['create_time'] =array('BETWEEN',array(strtotime($_REQUEST['start']),strtotime($_REQUEST['end'])+24*60*60-1));
            unset($_REQUEST['start']);unset($_REQUEST['end']);
        }
        if(isset($_REQUEST['pay_way'])){
            $map['pay_way']=$_REQUEST['pay_way'];
            unset($_REQUEST['pay_way']);
        }
        if(isset($_REQUEST['pay_status'])){
            $map['pay_status']=$_REQUEST['pay_status'];
            unset($_REQUEST['pay_status']);
        }
        $map1=$map;
        $map1['pay_status']=1;
        $total=null_to_0(D(self::model_name)->where($map1)->sum('pay_amount'));
				$map1['_string'] = 'create_time '.total(1);				
        $ttotal=null_to_0(D(self::model_name)->where($map1)->sum('pay_amount'));
				$map1['_string'] = 'create_time '.total(5);
        $ytotal=null_to_0(D(self::model_name)->where($map1)->sum('pay_amount'));
        $this->assign('total',$total);
        $this->assign('ttotal',$ttotal);
        $this->assign('ytotal',$ytotal);
        parent::order_lists(self::model_name,$_GET["p"],$map);
    }
}
