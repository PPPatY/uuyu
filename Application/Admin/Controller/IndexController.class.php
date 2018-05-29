<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Admin\Controller;
use User\Api\UserApi as UserApi;

/**
 * 后台首页控制器
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
class IndexController extends AdminController {

    /**
     * 后台首页
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function index(){
    	if(session('user_auth.uid')){
    		$data=M('Member')
    			->field('uid,nickname,us.last_login_time,us.last_login_ip,login')
    			->join('sys_ucenter_member as us on sys_member.uid = us.id')
    			->where(array('uid'=>session('user_auth.uid')))
    			->find();
			header("Content-type: text/html; charset=utf-8");
    		if(is_administrator()){
    			$data['group']='超级管理员';
    		}else{
                $data['group'] = get_auth_group_name($data['uid']);
    		}
    	}
    	$this->assign('data',$data);
      $this->indextt();
        $this->meta_title = '管理首页';
        $this->display();
    }
    public function indextt(){
        $user = M("User","tab_");
        $game = M("Game","tab_");
        $spend = M('Spend',"tab_");
        $deposit = M('Deposit',"tab_");
        $apply = M('Apply',"tab_");
        $promote = M("Promote","tab_");

       if($gameso){
          $gameso=implode(',',array_column($gameso, 'game_id'));
          $sourcemap['id']=array('not in',$gameso);
        }else{
           $sourcemap['id']=0;
        }
        //游戏原包管理
        $gac=$game->field('game_name')->where($sourcemap)->order('create_time desc')->select();
        $tishi['gac']=$gac;
        //代充额度
        $prolc=$promote
               ->field('account,pay_limit')
               ->where(array('pay_limit'=>array('lt',10),'set_pay_time'=>array('gt',0)))
               ->select();
        $tishi['prolc']=$prolc;
        //返利设置
        $map_rebc['endtime'] = array(array('neq',0),array('lt',time()), 'and') ;
        $rebc=M('Rebate','tab_')
              ->field('game_name,endtime')
              ->where($map_rebc)
              ->select();
        $tishi['rebc']=$rebc;
        //礼包数量
        $giftc=M('Giftbag','tab_')
              ->field('game_name,novice,giftbag_name')
              ->where(array('status'=>1))
              ->select();
        foreach ($giftc as $key => $value) {
            $novc=arr_count($value['novice']);
            if($novc>10){
                unset($giftc[$key]);
            }
        }
        //渠道礼包
        $pgiftc=M('promote_gift','tab_')
              ->field('game_name,novice,giftbag_name')
              ->where(array('status'=>1))
              ->select();
        foreach ($pgiftc as $key => $value) {
            $novc=arr_count($value['novice']);
            if($novc>10){
                unset($pgiftc[$key]);
            }
        }
        $tishi['giftc']=$giftc;
        $tishi['pgiftc']=$pgiftc;
        $this->assign('tishi',$tishi);
        // $this->display('index');
    }
    public function savekuaijie(){
      $newstr['kuaijie_value']=substr($_POST['kuaijie'],0,strlen($_POST['kuaijie'])-1);
      $data=M('Member')->where(array('uid'=>UID))->save($newstr);
      if($data!==false){
        $this->ajaxReturn(array('status'=>1));
      }else{
        $this->ajaxReturn(array('status'=>0));
      }
    }
    public function kuaijie(){
      $this->display();
    }
}
