<?php

namespace Home\Controller;
use OT\DataDictionary;
use User\Api\PromoteApi;
use Admin\Controller\StatController;

/**
 * 前台首页控制器
 * 主要获取首页聚合数据
 */
class QueryController extends BaseController {

    public function recharge($isbd=0,$p=0)
    {
        $pro_id=get_prmoote_chlid_account(session('promote_auth.pid'));
        foreach ($pro_id as $key => $value) {
            $pro_id1[]=$value['id'];
        }
        if(!empty($pro_id1)){
            $pro_id2=array_merge($pro_id1,array(get_pid()));
        }else{
            $pro_id2=array(get_pid());
        }
				$_REQUEST = array_trim($_REQUEST);
        $map['promote_id'] = array('in',$pro_id2);
        if(isset($_REQUEST['user_account'])){
            $map['user_account']=array('like','%'.str_replace('%','\%',$_REQUEST['user_account']).'%');
        }
        if(isset($_REQUEST['pay_order_number'])){
            $map['pay_order_number']=array('like','%'.str_replace('%','\%',$_REQUEST['pay_order_number']).'%');
        }
        if(!empty($_REQUEST['game_appid'])&&$_REQUEST['game_appid']!=''){
            $map['game_appid']=$_REQUEST['game_appid'];
        }
        if($_REQUEST['promote_id']>0){
            $map['promote_id']=$_REQUEST['promote_id'];
        }
        if(!empty($_REQUEST['time_start'])&&!empty($_REQUEST['time_end'])){
            $map['pay_time']  =  array('BETWEEN',array(strtotime($_REQUEST['time_start']),strtotime($_REQUEST['time_end'])+24*60*60-1));
        }elseif(!empty($_REQUEST['time_start'])){
            $map['pay_time']  =  array('egt',strtotime($_REQUEST['time_start']));
        }elseif(!empty($_REQUEST['time_end'])){
            $map['pay_time']  =  array('lt',strtotime($_REQUEST['time_end'])+24*60*60);
        }
        $uar = $_REQUEST;
				
        unset($uar['isbd']);
        if($isbd==0){
            $map['pay_way'] = array('egt',0);
        }else{
            $map['pay_way'] = array('lt',0);
        }

        $map['pay_status'] = 1;
        $map['is_check']=array('neq',2);
        
        $pay_amount = M('spend',"tab_")->where($map)->sum('pay_amount');
        $cost = M('spend',"tab_")->where($map)->sum('cost');
        $this->assign("pay_amount",$pay_amount);
        $this->assign("cost",$cost);
        $this->meta_title = "用户充值";
        $this->assign('promote_id',$_REQUEST['promote_id']);
        $this->assign('game_id',$_REQUEST['game_appid']);
				
				
        $this->lists("Spend",$p,$map,'充值明细');
    }
    public function cha_userarpu($p=0){
        if($_REQUEST['isbd']==1){
            $isbdpw['pay_way'] = array('neq',-1);
        }else{
            unset($isbdpw['pay_way']);
        }
        $page = intval($p);
        $page = $page ? $page : 1; //默认显示第一页数据arraypage
        $arraypage = $page ? $page : 1; //默认显示第一页数据
        $row = 10;
        $time = $_REQUEST['time'];
        $promote_id = $_REQUEST['promote_id'];
        $join = "left join tab_user u on u.fgame_id = tab_game.id";
        if($time==''){
            $this->error('参数错误，缺少时间');
        }else{
            $map['register_time']=array('between',array(strtotime($time),strtotime($time)+24*60*60-1));
        }
        if($promote_id!=''){
            $map_list['promote_id']=$promote_id;
            $map['promote_id']=$promote_id;
            $join .= " AND u.promote_id = {$promote_id}";
        }        
        $data=M('Game','tab_')->field('id as game_id, game_name')->order('id desc')->select();
        foreach ($data as $key => $value) {
            $game_id = $value['game_id'];
            $map_list['game_id']=$game_id;
            $user=M('User','tab_');
            $spend=M('spend','tab_');
            //新增人数
            $rdata=$user
                    ->field('count(id) as register_num')
                    ->where(array('fgame_id'=>$game_id))
                    ->where($map)
                    ->find();
            $data[$key]['register_num']=$rdata['register_num'];
            //活跃玩家
            $data[$key]['act_user'] = $this->count_act_user($time,$game_id,$promote_id);
            //1日留存
            $mapl=$map_list;
            $mapl["FROM_UNIXTIME(register_time,'%Y-%m-%d')"] = $time;
            empty($promote_id) || $mapl['tab_user.promote_id']=$mapl['promote_id'];
            unset($mapl['promote_id']);
            $login_time = date('Y-m-d', strtotime("+1 day",strtotime($time)));
            $num = $user
                ->field('count(DISTINCT tab_user.id) as num')
                ->join("right join tab_user_login_record as ur on ur.user_id = tab_user.id and FROM_UNIXTIME(ur.login_time,'%Y-%m-%d') = '{$login_time}'")
                ->where($mapl)
                ->find();
            $data[$key]['keep_num'] = round($num['num']/$data[$key]['register_num'],4)*100;
            //充值
            $mapl = $map_list;
            empty($game_name ) || $mapl['game_name'] = array('like','%'.$game_name.'%');
            empty($promote_id) || $mapl['promote_id'] = $promote_id;
            $mapl['pay_status'] = 1;
            $mapl["FROM_UNIXTIME(pay_time,'%Y-%m-%d')"] = $time;
            $spend = $spend->field("IFNULL(sum(pay_amount),0) as money,IFNULL(count(distinct user_id),0) as people")->where($isbdpw)->where($mapl)->find();
            $data[$key]['spend'] = $spend['money'];
            //付费玩家数
            $data[$key]['spend_people'] = $spend['people'];
            //付费率
            $data[$key]['spend_rate'] = round($data[$key]['spend_people']/$data[$key]['act_user'],4)*100;
            //ARPU
            $data[$key]['ARPU'] = round($data[$key]['spend']/$data[$key]['act_user'],2);
            //ARPPU
            $data[$key]['ARPPU'] = round($data[$key]['spend']/$data[$key]['spend_people'],2);
            if($data[$key]['register_num']==0&&$data[$key]['act_user']==0&&$data[$key]['keep_num']==0&&$data[$key]['spend']==0&&$data[$key]['spend_people']==0){
                unset($data[$key]);
            }
        }
        $count=count($data);
        if($count > $row){
                $page = new \Think\Page($count, $row);
                $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
                $this->assign('_page', $page->show());
            }
        $size=$row;//每页显示的记录数
        $pnum = ceil(count($data) / $size); //总页数，ceil()函数用于求大于数字的最小整数
        //用array_slice(array,offset,length) 函数在数组中根据条件取出一段值;array(数组),offset(元素的开始位置),length(组的长度)
        $data = array_slice($data, ($arraypage-1)*$size, $size);
        $this->assign('list_data',$data);
        $this->display();
    }

    /**
     * 获取活跃用户数
     * @param $time
     */
    protected function count_act_user($time,$game_id="",$promote_id=""){
        $map["FROM_UNIXTIME(tab_user_login_record.login_time,'%Y-%m-%d')"] = $time;
        $map1["FROM_UNIXTIME(register_time,'%Y-%m-%d')"]=$time;
        empty($game_id) || $map['game_id'] = $game_id;
        empty($game_id) || $map1['fgame_id'] = $game_id;
        if(!empty($promote_id)){
            $user=M('User','tab_')->field('id')->where(array('promote_id'=>$promote_id))->select();
            if(!empty($user)){
                $user=implode(',',array_column($user,'id'));
                $map['user_id']=array('in',$user);
            }else{
                $map['user_id']=array('eq',0);
            }
            $map1['id']=array('in',$user);
        };
        $data = M('user_login_record','tab_')
                ->field('user_id')
                ->where($map)
                ->join('tab_user on tab_user.id = tab_user_login_record.user_id')
                ->group('user_id')
                ->select();
        $user_id = array_column($data,'user_id');
        $data=count($user_id);
        return $data;
    }

    public function register($p=0){
        $pro_id=get_prmoote_chlid_account(session('promote_auth.pid'));
				$_REQUEST = array_trim($_REQUEST);
        foreach ($pro_id as $key => $value) {
            $pro_id1[]=$value['id'];
        }
        if(!empty($pro_id1)){
            $pro_id2=array_merge($pro_id1,array(get_pid()));
        }else{
            $pro_id2=array(get_pid());
        }
        $map['promote_id'] = array('in',$pro_id2);
        if(isset($_REQUEST['account'])){
            $map['account']=array('like','%'.str_replace('%','\%',$_REQUEST['account']).'%');
        }
        if(isset($_REQUEST['game_id'])&&$_REQUEST['game_id']!=0){
            $map['fgame_id']=$_REQUEST['game_id'];
        }
        if($_REQUEST['promote_id']>0){
            $map['promote_id']=$_REQUEST['promote_id'];
        }
        if(!empty($_REQUEST['time-start'])&&!empty($_REQUEST['time-end'])){
            $map['register_time']  =  array('BETWEEN',array(strtotime($_REQUEST['time-start']),strtotime($_REQUEST['time-end'])+24*60*60-1));
        }elseif(!empty($_REQUEST['time-start'])){
            $map['register_time']  =  array('egt',strtotime($_REQUEST['time-start']));
        }elseif(!empty($_REQUEST['time-end'])){
            $map['register_time']  =  array('lt',strtotime($_REQUEST['time-end'])+24*60*60);
        }
        if(!empty($_REQUEST['start'])&&!empty($_REQUEST['end'])){
            $map['register_time']  =  array('BETWEEN',array(strtotime($_REQUEST['start']),strtotime($_REQUEST['end'])+24*60*60-1));
        }
        $map['is_check']=array('neq',2);
        $this->assign('promote_id',$_REQUEST['promote_id']);
        $this->lists("User",$p,$map,'注册明细');
    }
    public function arpu_analysis($p=0){
        $stat = A('Admin/Stat','Event');
        $stat->userarpu($p,1);
    }
    public function retention_analysis($p=0){
        $stat = A('Admin/Stat','Event');
        $stat->userretention($p,1);
    }

    /**
    *我的对账单
    */
    public function bill(){
        $map['promote_id']=get_pid();
        if(isset($_REQUEST['bill_number'])&&!empty($_REQUEST['bill_number'])){
            $map['bill_number']=$_REQUEST['bill_number'];
        }
        if(isset($_REQUEST['game_id'])&&!empty($_REQUEST['game_id'])){
            $map['game_id']=$_REQUEST['game_id'];
        }
        if(!empty($_REQUEST['timestart'])&&!empty($_REQUEST['timeend'])){
            $map['bill_start_time'] = array('egt',strtotime($_REQUEST['timestart']));
            $map['bill_end_time'] = array('elt',strtotime($_REQUEST['timeend'])+24*3600-1);
        }  
        $model=array(
            'm_name'=>'bill',
            'map'   =>$map,
            'template_list'=>'bill',
            'title' =>'我的对账单',
        );
        $user = A('User','Event');
        $user->bill_list($model,$_GET['p']);
    }


    /**
    *我的结算
    */
    public function my_earning($p=1){
       $pro_map['id']=get_pid();
       $pro=M("promote","tab_")->where($pro_map)->find();
       if($pro['parent_id']==0){
            $map['promote_id']=get_pid();
            if(isset($_REQUEST['settlement_number'])&&!empty($_REQUEST['settlement_number'])){
                $map['settlement_number']=array('like','%'.str_replace('%','\%',$_REQUEST['settlement_number']).'%');
            }
            if(isset($_REQUEST['timestart'])&&!empty($_REQUEST['timestart'])){
            	$starttime = strtotime($_REQUEST['timestart']);
            	$map['starttime'] = array('egt',$starttime);
            }
            if(isset($_REQUEST['endtime'])&&!empty($_REQUEST['endtime'])){
            	$endtime = strtotime($_REQUEST['timeend'])+24*60*60-1;
            	$map['endtime'] = array('elt',$endtime);
            }
            $this->assign('starttime',$starttime);
            $this->assign('endtime',$endtime);
            $model=array(
                'm_name'=>'settlement',
                'map'   =>$map,
                'template_list'=>'my_earning',
                'order'=>'create_time desc',
            	'group'=>'settlement_number',
                'title' =>'我的结算',

            );
        }else{
            $model=array(
                'm_name'=>'son_settlement',
                'map'   =>$map,
                'template_list'=>'my_earning',
            	'group'=>'settlement_number',
                'title' =>'我的结算',
            );
        }
        $user = A('User','Event');
        $this->assign("parent_id",$pro['parent_id']);
        $user->shou_list($model,$p);
    }

    /*
     * 渠道结算详情
     */
    public function settlemeng_detail(){
    	$settlemeng_number = $_REQUEST;
    	if(empty($settlemeng_number['settlement_number'])){
    		$this->error("数据错误！",U('Query/my_earning'));
    	}
    	$map = $settlemeng_number;
    	$data = M('Settlement','tab_')->where($map)->field('starttime,endtime,game_id,game_name,sum_money,settlement_number,pattern,total_money,ratio,money')->select();
    	$this->assign('list_data',$data);
    	$this->display();
    }
    /**
    *子渠道结算单
    */
    public function son_earning_($p=1){
        if (PLEVEL == 0) {
            if(isset($_REQUEST['timestart']) && isset($_REQUEST['timeend']) && !empty($_REQUEST['timestart']) && !empty($_REQUEST['timeend'])){
                $starttime = strtotime($_REQUEST['timestart']);
                $endtime = strtotime($_REQUEST['timeend'])+24*60*60-1;
                $this->assign('starttime',$starttime);
                $this->assign('endtime',$endtime);
                $map[0]['register_time']  =  array('BETWEEN',array($starttime,$endtime));               
                $map[1]['pay_time']  =  array('BETWEEN',array($starttime,$endtime));
                unset($_REQUEST['timestart']);unset($_REQUEST['timeend']);
                
                $map[1]['parent_id'] = $map[0]['u.parent_id']=PID;
                if(isset($_REQUEST['ch_promote_id'])&&!empty($_REQUEST['ch_promote_id'])){
                    $map[1]['s.promote_id'] = $map[0]['u.promote_id']=$_REQUEST['ch_promote_id'];
                }
                $model = array(
                    'title'  => '子渠道结算单',
                    'template_list' =>'son_earning',
                );
                $user = A('User','Event');
                $user->check_bill($model,$p,$map);
            } else {
                $this->display(); 
            }           
        } else {
            $model = array(
                'm_name' => 'SonSettlement',
                'order'  => 'id ',
                'title'  => '结算账单',
                'template_list' =>'son_earning',
            );
            
            $user = A('User','Event');
            $user->money_list($model,$p);
        }        
    }

    //子渠道结算单
    public function son_list($p=0){
        if(!empty($_REQUEST['timestart'])&&!empty($_REQUEST['timeend'])){
          $map['settlement_start_time'] = array('egt',strtotime($_REQUEST['timestart']));
          $map['settlement_end_time'] = array('elt',strtotime($_REQUEST['timeend'])+24*60*60-1);
          $_GET['timestart']=$_REQUEST['timestart'];
          $_GET['timeend']=$_REQUEST['timeend'];
        } elseif(!empty($_REQUEST['timestart'])) {
          $map['settlement_start_time'] = array('egt',strtotime($_REQUEST['timestart']));
          $_GET['timestart']=$_REQUEST['timestart'];
        } elseif(!empty($_REQUEST['timeend'])) {
          $map['settlement_end_time'] = array('elt',strtotime($_REQUEST['timeend'])+24*60*60-1);
          $_GET['timeend']=$_REQUEST['timeend'];
        }
               
        
        if(!empty($_REQUEST['jtimestart']) && !empty($_REQUEST['jtimeend'])) {
          $map['create_time']=array(array('egt',strtotime($_REQUEST['jtimestart'])),array('elt',strtotime($_REQUEST['jtimeend'])+24*60*60-1));
          $_GET['jtimestart']=$_REQUEST['jtimestart'];
          $_GET['jtimeend']=$_REQUEST['jtimeend'];
        } elseif (!empty($_REQUEST['jtimestart'])) {
          $map['create_time']=array('egt',strtotime($_REQUEST['jtimestart']));
          $_GET['jtimestart']=$_REQUEST['jtimestart'];
        } elseif (!empty($_REQUEST['jtimestart'])) {
          $map['create_time']=array('elt',strtotime($_REQUEST['jtimeend'])+24*60*60-1);
          $_GET['jtimeend']=$_REQUEST['jtimeend'];
        }
        
        if($_REQUEST['ch_promote_id']>0) {
          
          $_GET['ch_promote_id']=$map['promote_id']=$_REQUEST['ch_promote_id'];
          
        } else {
          $zi_p=get_zi_promote_id(PID);
          $map['promote_id']=array('in',"$zi_p");          
        }
        
        
          
          $model = array(
                'm_name' => 'SonSettlement',
                'order'  => 'create_time desc,id desc',
          		'group'=>'settlement_number',
                'title'  => '结算记录',
                'template_list' =>'son_list',
            );
            $user = A('User','Event');
            $user->money_list($model,$p,$map);
    }
    
    
    /**
    *子渠道结算单
    */
    public function son_earning($p=0){
        $this->assign('setdate',date("Y/m/d",strtotime("-1 day")));
        if (PLEVEL == 0) {
        	$data = $_REQUEST;
        	if (empty($data['isbd'])){
        		$map['s.pay_way'] = array('egt',0);
        	}
        	$this->assign('isbd',$data['isbd']);
        	unset($data['isbd']);
            if(isset($_REQUEST['timestart']) && isset($_REQUEST['timeend']) && !empty($_REQUEST['timestart']) && !empty($_REQUEST['timeend'])){
                $starttime = strtotime($_REQUEST['timestart']);
                $endtime = strtotime($_REQUEST['timeend'])+24*60*60-1;
                $this->assign('starttime',$starttime);
                $this->assign('endtime',$endtime);
                $mapp['u.register_time']  =  array('BETWEEN',array($starttime,$endtime));               
                $map['s.pay_time']  =  array('BETWEEN',array($starttime,$endtime));
                $map['s.pay_status']  =  1;
                $map['s.sub_status']  =  0;
                unset($_REQUEST['timestart']);unset($_REQUEST['timeend']);
                // $map[1]['parent_id'] =PID;
                if(isset($_REQUEST['ch_promote_id'])&&!empty($_REQUEST['ch_promote_id'])){
                    $map['s.promote_id']=$mapp['u.promote_id']=$_REQUEST['ch_promote_id'];
                }else{
                    $promote_child = get_zi_promote_id(PID);
                    if(empty($promote_child)){
                        $this->error('该渠道无子渠道');
                    }
                    $this->assign('promote_child',$promote_child);
                     $map['s.promote_id']=$mapp['u.promote_id']=array('in',$promote_child);
                }
                $model = array(
                    'fields' =>'sum(s.pay_amount) as total_amount,s.promote_account,s.promote_id,s.game_name,s.game_id,s.sub_status',
                    'm_name' =>'Spend',
                    'title'  => '子渠道结算单',
                    'template_list' =>'son_earning',
                    'join'      =>'tab_apply on tab_Spend.game_id=tab_apply.game_id and tab_Spend.promote_id=tab_apply.promote_id',
                    'group' =>'s.promote_id,s.game_id',
                );
                $mmap=array($mapp,$map);
                $user = A('User','Event');
                $user->check_bill_($model,$p,$mmap);
            } else {
                $this->meta_title='子渠道结算';
                $this->display(); 
            }           
        } else {
              if(isset($_REQUEST['settlement_number'])&&!empty($_REQUEST['settlement_number'])){
                $map['settlement_number']=trim($_REQUEST['settlement_number']);
            }
            if(isset($_REQUEST['game_id'])&&!empty($_REQUEST['game_id'])){
                $map['game_id']=$_REQUEST['game_id'];
            }
            if(isset($_REQUEST['pattern'])&&$_REQUEST['pattern']!=''){
                $map['pattern']=$_REQUEST['pattern'];
            }
            if(!empty($_REQUEST['time-start'])&&!empty($_REQUEST['time-end'])){
                $map['settlement_start_time'] = array('egt',strtotime($_REQUEST['time-start']));
                $map['settlement_end_time'] = array('elt',strtotime($_REQUEST['time-end'])+24*60*60-1);
            }  
            $model = array(
                'm_name' => 'SonSettlement',
                'order'  => 'id ',
            	'group'=>'settlement_number',
                'title'  => '结算账单',
                'template_list' =>'son_earning',
            );
            $map['promote_id']=PID;
            $user = A('User','Event');
            $user->money_list($model,$p,$map);
        }        
    }
    public function generatesub() {

    	$data = $_REQUEST;
    	$ids = $_POST;
      if (!empty($ids)) {
        
    	$settlement_number = 'js_'.date('YmdHis',time()).rand(100,999);
    	foreach ($ids as $va){
    		$game_data = explode(',', $va[0]);
    		$gdata['settlement_number'] = $settlement_number;
    		$gdata['settlement_start_time'] = strtotime($_REQUEST['timestart']);
    		$gdata['settlement_end_time'] = strtotime($_REQUEST['timeend'])+24*60*60-1;
    		$gdata['game_id'] = $game_data[0];
    		$gdata['game_name'] = get_game_name($game_data[0]);
    		$gdata['promote_id'] = $game_data[6];
    		$gdata['promote_account'] = get_promote_account($game_data[6]);
    		$game_data[1] == "CPS" ? $gdata['pattern'] = 0 : $gdata['pattern'] = 1;
    		$gdata['sum_money'] = $game_data[5];
    		$gdata['reg_number'] = $game_data[4];
    		$gdata['ratio'] = $game_data[2];
    		$gdata['money'] = $game_data[3];
    		$gdata['create_time'] = NOW_TIME;
    		$gdata['isbd'] = $data['isbd'];
    	}
    	unset($data);
    	if ($gdata['pattern'] == 0) {
    		$cps = $gdata['ratio'];
    		$gdata['jie_money'] = round(($cps*$gdata['sum_money'])/100,2);
    	} elseif ($gdata['pattern'] == 1) {
    		$cpa = $gdata['money'];
    		$gdata['jie_money'] = $cpa*$gdata['reg_number'];
    	} else {
    		$this->error("操作失败",'',true);
    	}
    	$settlemt_data[] = $gdata;
    	
    	$user = M('User',"tab_");
    	$spend = M('Spend',"tab_");
    	$bill = M('SonSettlement',"tab_");
    	
    	foreach ($settlemt_data as $data){
    		$result = get_son_settlement_stauts($data['game_id'],$data['promote_id'],$data['settlement_start_time'],$data['settlement_end_time']);
    		if($result){
    			$game_name = $data['game_name'].'、'.$game_name;
    		}else{
    			$start = $data['settlement_start_time'];
    			$end = $data['settlement_end_time'];
    			$map0['register_time'] = array('BETWEEN',array($start,$end));
    			$map1['pay_time'] = array('BETWEEN',array($start,$end));
    			$map1['sub_status'] = $map0['sub_status'] = 0;
    			$map1['game_id'] = $map0['game_id'] = $data['game_id'];
    			$map1['promote_id'] = $map0['promote_id'] = $data['promote_id'];
    			$map0['fgame_id']=$data['game_id'];
    			$map1['game_id']=$data['game_id'];
    			$partake = array('sub_status'=>1);
    			$user->where($map0)->save($partake);
    			$spend->where($map1)->save($partake);
    			$bill->add($data);
    			$user->commit();$spend->commit();$bill->commit();
    		}
    	}
    		$this->success('生成结算单成功！已经结算的游戏不可结算111',U('son_list'),true);
      
      } else {
        $this->error('请选择要结算的项目',true);
      }
    }
    /*
     *二级推广员结算单详情 
     */
    public function son_settlemeng_detail(){
    	$settlement_number = $_REQUEST;
    	if(empty($settlement_number['settlement_number'])){
    		$this->error("数据错误！",U('Query/son_earning'));
    	}
    	$map = $settlement_number;
    	$data = M('Son_settlement','tab_')->where($map)->select();
    	$this->assign('list_data',$data);
    	$this->display();
    }
    /*
     *二级推广员审核提现
     */
    public function change_son_settlement(){
    	$data = $_REQUEST;
    	$map['settlement_number'] = $data['settlement_number'];
    	M("withdraw","tab_")->where($map)->save(array('status'=>$data['type']));
    	$res = M("Son_settlement","tab_")->where($map)->save(array('ti_status'=>$data['type'],'ti_time'=>NOW_TIME));
    	if($res){
    		$this->ajaxReturn(array('status'=>1,'msg'=>"审核成功！"));
    	}else{
    		$this->ajaxReturn(array('status'=>0,'msg'=>"审核失败！"));
    	}
    }

    /*
     * 子渠道申请提现
     */
    public function channel_son_settlement($settlement_number){
    	
    	$map['settlement_number'] = $settlement_number;
    	$with= M("withdraw","tab_");
    	$status = $with->where($map)->find();
    	if($status){
    		$this->ajaxReturn(array('status'=>0,'msg'=>"请不要重复申请！"));
    	}
    	//结算订单
    	$seet=M("Son_settlement","tab_")->where($map)->find();
    	if($seet){
    		$data['settlement_number'] = $settlement_number;
    		$data['sum_money'] = get_son_settlemt_sum($settlement_number, sum_money);
    		$data['promote_id'] = $seet['promote_id'];
    		$data['promote_account'] = get_promote_account($seet['promote_id']);
    		$data['create_time'] = NOW_TIME;
    		$data['status'] = 0;
    		$with->add($data);
    		M("Son_settlement","tab_")->where($map)->save(array('ti_status'=>0,'ti_create_time'=>NOW_TIME));
    		$this->ajaxReturn(array('status'=>1,'msg'=>"申请成功！"));
    	}else{
    		$this->ajaxReturn(array('status'=>0,'msg'=>"申请失败！"));
    	}
    }

    //申请提现
    public function apply_withdraw($id,$op=0){
        $map['id']=$id;				
        $with= M("withdraw","tab_");        
        $seet=M("settlement","tab_")->where($map)->find();
        $with_map['settlement_number']=$seet['settlement_number'];
        $fid=$with->where($with_map)->find();
        if($fid==null){
					$add['settlement_number']=$seet['settlement_number'];
					$add['sum_money']=$seet['sum_money'];
					$add['promote_id']=$seet['promote_id'];
					$add['promote_account']=$seet['promote_account'];
					$add['create_time']=time();
					$add['status']=0;
					$with->add($add);
					M("settlement","tab_")->where($map)->save(array('ti_status'=>0));
					echo json_encode(array("status"=>1));
        }else{
					if ($op>0) {
						$with->where($with_map)->setField('status',0);
						M("settlement","tab_")->where($map)->setField('ti_status',0);
						echo json_encode(array("status"=>1));
					} else {
						
						echo json_encode(array("status"=>0));
					}
					
        }

    }
}