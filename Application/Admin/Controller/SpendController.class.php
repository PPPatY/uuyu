<?php

namespace Admin\Controller;
use User\Api\UserApi as UserApi;
use Org\UcenterSDK\Ucservice;
/**
 * 后台首页控制器
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
class SpendController extends ThinkController {
    const model_name = 'Spend';
    //充值列表
    public function lists(){
        if(isset($_REQUEST['user_account'])){
            $map['user_account']=array('like','%'.trim($_REQUEST['user_account']).'%');
            unset($_REQUEST['user_account']);
        }
        if(isset($_REQUEST['spend_ip'])){
            $map['spend_ip']=array('like','%'.trim($_REQUEST['spend_ip']).'%');
            unset($_REQUEST['spend_ip']);
        }

				
				if (!empty($_REQUEST['timestart']) && !empty($_REQUEST['timeend'])) {
					$map['pay_time'] = array('BETWEEN',array(strtotime($_REQUEST['timestart']),strtotime($_REQUEST['timeend'])+24*60*60-1));
					
				} elseif (!empty($_REQUEST['timestart']) ) {
					$map['pay_time'] = array('BETWEEN',array(strtotime($_REQUEST['timestart']),time()));
				
				} elseif (!empty($_REQUEST['timeend']) ) {
					$map['pay_time'] = array('elt',strtotime($_REQUEST['timeend'])+24*60*60-1);
					
				}
				
        if(isset($_REQUEST['start'])&&isset($_REQUEST['end'])){
            $map['pay_time'] =array('BETWEEN',array(strtotime($_REQUEST['start']),strtotime($_REQUEST['end'])+24*60*60-1));
            unset($_REQUEST['start']);unset($_REQUEST['end']);
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
            $map['pay_status']=$_REQUEST['pay_status'];
            unset($_REQUEST['pay_status']);
        }
        if(isset($_REQUEST['pay_way'])){
            $map['pay_way']=$_REQUEST['pay_way'];
            unset($_REQUEST['pay_way']);
        }
        if(isset($_REQUEST['pay_game_status'])){
            $map['pay_game_status']=$_REQUEST['pay_game_status'];
            unset($_REQUEST['pay_game_status']);
        }
        $map['order']='pay_time DESC';
        $map1=$map;
        $map1['pay_status']=1;
        $total=null_to_0(D(self::model_name)->where($map1)->sum('pay_amount'));
        $ttotal=null_to_0(D(self::model_name)->where('pay_time'.total(1))->where(array('pay_status'=>1))->sum('pay_amount'));
        $ytotal=null_to_0(D(self::model_name)->where('pay_time'.total(5))->where(array('pay_status'=>1))->sum('pay_amount'));
        $this->assign('total',$total);
        $this->assign('ttotal',$ttotal);
        $this->assign('ytotal',$ytotal);
        parent::order_lists(self::model_name,$_GET["p"],$map);
    }

    
    public function uc_statistics($p=1){
        if(isset($_REQUEST['timestart']) && isset($_REQUEST['timeend'])){
                $map.='pay_time between '.strtotime($_REQUEST['timestart']).' and '.(strtotime($_REQUEST['timeend'])+24*60*60-1).' and ';
        }
        if(isset($_REQUEST['game_name'])){
            $map.='game_name like '.'"'.'%'.$_REQUEST['game_name'].'%'.'" and ';
            $map1.='game_name like '.'"'.'%'.$_REQUEST['game_name'].'%'.'" and ';
            $map2.='game_name like '.'"'.'%'.$_REQUEST['game_name'].'%'.'" and ';
            unset($_REQUEST['game_name']);
        }
        $map.=" version=6 ";
        $uc=new Ucservice();
        $page=$p;
        $data=$uc->uc_recharge_select($page,10,$map);
        $map1.='pay_time'.total(1).' and ';
        $map1.=" version=6 ";
        $map2.='pay_time'.total(5).' and ';
        $map2.=" version=6 ";
        //今天
        $ttotal=$uc->uc_recharge_select($page,10,$map1)['total']?$uc->uc_recharge_select($page,10,$map1)['total']:0;
        //昨天
        $ytotal=$uc->uc_recharge_select($page,10,$map2)['total']?$uc->uc_recharge_select($page,10,$map2)['total']:0;
        //总共
        $total=$data['total']?$data['total']:0;
        //该叶
        $pagetotal=$data['totalpage'][0]['totalpage']?$data['totalpage'][0]['totalpage']:0;
        $this->meta_title = 'Uc充值列表';
        $this->assign('ttotal',$ttotal);
        $this->assign('ytotal',$ytotal);
        $this->assign('pagetotal',$pagetotal);
        $this->assign('total',$total);
        $count=$data['count'];
        $row=10;
        if($count > $row){
            $page = new \Think\Page($count, $row);
            $page->setConfig('theme','%UP_PAGE% %FIRST% %LINK_PAGE% %END% %DOWN_PAGE% %HEADER%');
            $this->assign('page', $page->show());
        }
        unset($data['count']);
        unset($data['total']);
        unset($data['totalpage']);
        $this->assign('data',$data);
        $this->display();
    }
}