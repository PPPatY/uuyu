<?php
// +----------------------------------------------------------------------
// | 徐州梦创信息科技有限公司—专业的游戏运营，推广解决方案.
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.vlcms.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: kefu@vlcms.com QQ：97471547
// +----------------------------------------------------------------------
namespace Admin\Event;

use Think\Controller;

/**
 * 后台首页控制器
 * 
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
class StatisticsEvent extends Controller
{
    public function data_order($nowtime,$othertime){
        if(I('isbd')==1){
            $isbdpw['pay_way'] = array('neq',-1);
        }else{
            $isbdpw['id'] = array('gt',0);
        }
        $user = M("User","tab_");
        $userplay = M("UserPlay","tab_");
        $userrecord = M("user_login_record","tab_");
        $spend = M('Spend',"tab_");
        //今日注册排行
        $ri_ug_order=$userplay->field('game_id as fgame_id,game_name as fgame_name,count(user_id) as cg')->where(array('play_time'.$nowtime))->where(array('game_id'=>array('gt',0)))->group('game_id')->order('cg desc')->limit(10)->select();
        $ri_ug_order=array_order($ri_ug_order);

        $yes_ug_order=$userplay->field('game_id,game_name,count(user_id) as cg')->where(array('play_time'.$othertime))->where(array('game_id'=>array('gt',0)))->group('game_id')->order('cg desc')->limit(10)->select();
        $yes_ug_order=array_order($yes_ug_order);
        
        foreach ($ri_ug_order as $key => $value) {
            $ri_ug_order[$key]['change']=-$value['rand'];
            foreach ($yes_ug_order as $k => $v) {
                if($value['fgame_id']==$v['fgame_id']){
                    $ri_ug_order[$key]['change']=$value['rand']-$v['rand'];
                }else{
                    $ri_ug_order[$key]['change']=$value['rand'];
                }
            }
        }
        // //今日活跃排行
        $active_sql1=$user
                            ->field('ur.*,min(ur.login_time)')
                            ->join('tab_user_login_record ur on ur.user_id = tab_user.id')
                            ->where(array('ur.game_id'=>array('gt',0)))
                            ->group('game_id,user_id')
                            ->buildsql();
        $ri_active_sql = "select r.game_id,r.game_name,count(user_id) as cg from {$active_sql1} r where r.login_time {$nowtime} and r.game_id > 0 group by r.game_id order by cg desc limit 10";
        $ri_active_order = M()->query($ri_active_sql);
        $ri_active_order=array_order($ri_active_order);
        $yes_active_sql = "select r.game_id,r.game_name,count(user_id) as cg from {$active_sql1} r where r.login_time {$othertime} and r.game_id > 0 group by r.game_id order by cg desc limit 10";
        $yes_active = M()->query($yes_active_sql);
        $yes_active=array_order($yes_active);
        foreach ($ri_active_order as $key => $value) {
            $ri_active_order[$key]['change']=-$value['rand'];
            foreach ($yes_active as $k => $v) {
                if($value['game_id']==$v['game_id']){
                    $ri_active_order[$key]['change']=$value['rand']-$v['rand'];
                }else{
                    $ri_active_order[$key]['change']=$value['rand'];
                }
            }
        }

        // //充值排行
        //spend
        $ri_spay_order=$spend->field('game_id,game_name,sum(pay_amount) as cg')->where(array('pay_time'.$nowtime))->where(array('game_id'=>array('gt',0)))->where(array('pay_status'=>1))->where($isbdpw)->group('game_id')->order('cg desc')->limit(10)->select();
        $ri_spay_order=array_order($ri_spay_order);

        $yes_spay=$spend->field('game_id,game_name,sum(pay_amount) as cg')->where(array('pay_status'=>1))->where(array('pay_time'.$othertime))->where(array('game_id'=>array('gt',0)))->where($isbdpw)->group('game_id')->order('cg desc')->select();
        $yes_spay=array_order($yes_spay);
        foreach ($ri_spay_order as $key => $value) {
            $ri_spay_order[$key]['change']=-$value['rand'];
            foreach ($yes_spay as $k => $v) {
                if($value['game_id']==$v['game_id']){
                    $ri_spay_order[$key]['change']=$value['rand']-$v['rand'];
                }
            }
        }
        $data['zhuce']=$ri_ug_order;
        $data['active']=$ri_active_order;
        $data['pay']=$ri_spay_order;
        return $data;
    }
}
