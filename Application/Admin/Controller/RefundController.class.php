<?php

namespace Admin\Controller;
use User\Api\UserApi as UserApi;

/**
 * 后台首页控制器
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
class RefundController extends ThinkController {
    public function lists(){
    	if(isset($_REQUEST['user_account'])){
    		$map['user_account']=array('like','%'.trim($_REQUEST['user_account']).'%');
    		unset($_REQUEST['user_account']);
    	}
    
    	if(isset($_REQUEST['time-start'])&&isset($_REQUEST['time-end'])){
            $map['pay_time'] =array('BETWEEN',array(strtotime($_REQUEST['time-start']),strtotime($_REQUEST['time-end'])+24*60*60-1));
            unset($_REQUEST['time-start']);unset($_REQUEST['time-end']);
        }
       
        if(isset($_REQUEST['game_name'])){
            if($_REQUEST['game_name']=='全部'){
                unset($_REQUEST['game_name']);
            }else{
            	$map['game_name']=$_REQUEST['game_name'];
            	unset($_REQUEST['game_name']);
            }
        }
        if(isset($_REQUEST['pay_order_number'])){
        	$map['pay_order_number']=array('like','%'.trim($_REQUEST['pay_order_number']).'%');
        	unset($_REQUEST['pay_order_number']);
        }
        if(isset($_REQUEST['pay_status'])){
            $map['tui_status']=$_REQUEST['pay_status'];
            unset($_REQUEST['pay_status']);
        }
        if(isset($_REQUEST['pay_way'])){
            $map['pay_way']=$_REQUEST['pay_way'];
            unset($_REQUEST['pay_way']);
        }
        $model = array(
            'm_name' => 'RefundRecord',
            'map'    => $map,
            'order'  =>'id desc',
            'title'  => '退款记录',
            'template_list' =>'lists',
        );
        $total=null_to_0(M('RefundRecord','tab_')->where(['tui_status'=>1])->sum('tui_amount'));
        $ttotal=null_to_0(M('RefundRecord','tab_')->where('pay_time'.total(1))->where(array('tui_status'=>1))->sum('tui_amount'));
        $ytotal=null_to_0(M('RefundRecord','tab_')->where('pay_time'.total(5))->where(array('tui_status'=>1))->sum('tui_amount'));
        $this->assign('total',$total);
        $this->assign('ttotal',$ttotal);
        $this->assign('ytotal',$ytotal);
        $user = A('User','Event');
        $user->user_join_($model,$_GET['p']);
    }

}