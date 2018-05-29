<?php

namespace Admin\Controller;
use User\Api\UserApi as UserApi;

/**
 * 后台首页控制器
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
class ProvideController extends ThinkController {
	const model_name = 'Provide';

   public function lists(){
        if(isset($_REQUEST['user_account'])){
            $map['user_account']=array('like','%'.trim($_REQUEST['user_account']).'%');
            unset($_REQUEST['user_account']);
        }

        if(isset($_REQUEST['start'])&&isset($_REQUEST['end'])){
            $map['create_time'] =array('BETWEEN',array(strtotime($_REQUEST['start']),strtotime($_REQUEST['end'])+24*60*60-1));
            unset($_REQUEST['start']);unset($_REQUEST['end']);
        }
				
				if (!empty($_REQUEST['timestart']) && !empty($_REQUEST['timeend'])) {
					$map['create_time'] = array('BETWEEN',array(strtotime($_REQUEST['timestart']),strtotime($_REQUEST['timeend'])+24*60*60-1));
					
				} elseif (!empty($_REQUEST['timestart']) ) {
					$map['create_time'] = array('BETWEEN',array(strtotime($_REQUEST['timestart']),time()));
					
				} elseif (!empty($_REQUEST['timeend']) ) {
					$map['create_time'] = array('elt',strtotime($_REQUEST['timeend'])+24*60*60-1);
					
				}
				
				
        if(isset($_REQUEST['game_name'])){
            if($_REQUEST['game_name']=='全部'){
                unset($_REQUEST['game_name']);
            }else{
                $map['game_name']=$_REQUEST['game_name'];
                unset($_REQUEST['game_name']);
            }
        }
        if(isset($_REQUEST['op_account'])){
            if($_REQUEST['op_account']=='全部'){
                unset($_REQUEST['op_account']);
            }else{
                $map['op_account']=$_REQUEST['op_account'];
                unset($_REQUEST['op_account']);
            }
        }
        if(I('group')==2){
            $map['coin_type'] =0;
        }else{
            $map['coin_type'] =-1;
        }
        $map1=$map;
        $map1['status']=1;
        $total=null_to_0(D(self::model_name)->where($map1)->sum('amount'));
        $ttotal=null_to_0(D(self::model_name)->where('create_time'.total(1))->where(array('pay_status'=>1))->where($map1)->sum('amount'));
        $ytotal=null_to_0(D(self::model_name)->where('create_time'.total(5))->where(array('pay_status'=>1))->where($map1)->sum('amount'));
        // $this->assign('dtotal',$dtotal);
        $this->assign('total',$total);
        $this->assign('ttotal',$ttotal);
        $this->assign('ytotal',$ytotal);
        parent::order_lists(self::model_name,$_GET["p"],$map);
    }
    public function bdfirstpay(){    
        $this->meta_title = '后台发放（玩家）';
        if(IS_POST){
            $type = $_REQUEST['type'];
            $firstpay = A('Firstpay','Event');
            switch ($type) {
                case 1:
                    $firstpay->add1();
                    break;
                case 2:
                    $firstpay->add2();
                    break;
                case 3:
                    $firstpay->add3();
                    break;
            }
        }   
        else{
            $this->display();
        }
    }

    public function ptbsenduser(){    
        $this->meta_title = '后台发放（玩家）';
        if(IS_POST){
            $type = $_REQUEST['type'];
            $firstpay = A('Ptbsend','Event');
            switch ($type) {
                case 1:
                    $firstpay->add1();
                    break;
                case 2:
                    $firstpay->add2();
                    break;
                case 3:
                    $firstpay->add3();
                    break;
            }
        }   
        else{
            $this->display();
        }
    }
    
    public function batch($ids){
        $list=M("provide","tab_");
        $map['id']=array("in",$ids);
        $map['status']=0;
        $pro=$list->where($map)->select(); 
        for ($i=0; $i <count($pro) ; $i++) { 
            if($pro[$i]['coin_type']==-1){
              $maps['user_id']=$pro[$i]['user_id'];
              $maps['game_id']=$pro[$i]['game_id'];
              $user=M("UserPlay","tab_")->where($maps)->setInc("bind_balance",$pro[$i]['amount']);
            }else{
                $maps['id']=$pro[$i]['user_id'];
                $user = M("User","tab_")->where($maps)->find();
                if($user){
                    $cz=M("User","tab_")->where($maps)->setInc("balance",$pro[$i]['amount']);
                }
            }
            $list->where($map)->setField("status",1);
        }
        $this->success("充值成功",U("lists",array('group'=>$_REQUEST['group'])));
    }

    public function delprovide($ids){
      $list=M("provide","tab_");
      $map['id']=array("in",$ids);
      $map['status']=0;
      $delete=$list->where($map)->delete();
       if($delete){
            $this->success("批量删除成功！",U("lists",array('group'=>$_REQUEST['group'])));
       }else{
       		 $this->error("批量删除失败！",U("lists",array('group'=>$_REQUEST['group'])));
        }
    }
}
