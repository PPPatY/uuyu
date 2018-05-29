<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: yangweijie <yangweijiester@gmail.com> <code-tech.diandian.com>
// +----------------------------------------------------------------------
namespace Addons\SiteStat;
use Common\Controller\Addon;

/**
 * 系统环境信息插件
 * @author thinkphp
 */
class SiteStatAddon extends Addon{

    public $info = array(
        'name'=>'SiteStat',
        'title'=>'站点统计信息',
        'description'=>'统计站点的基础信息',
        'status'=>1,
        'author'=>'thinkphp',
        'version'=>'0.1'
    );

    public function install(){
        return true;
    }

    public function uninstall(){
        return true;
    }

    //实现的AdminIndex钩子方法
    public function AdminIndex($param){
        $config = $this->getConfig();
        $this->assign('addons_config', $config);
        if($config['display']){
            $user = M("User","tab_");
            $userlogin = M("UserLoginRecord","tab_");
            $game = M("Game","tab_");
            $spend = M('Spend',"tab_");
            $deposit = M('Deposit',"tab_");
            $promote = M("Promote","tab_");
            $yesterday = $this->total(5);
            $today = $this->total(1);
            $week = $this->total(9);
            $month = $this->total(3);

            $monstart=mktime(0,0,0,date('m'),1,date('Y'));
            $monend=mktime(0,0,0,date('m')+1,1,date('Y'))-1;

            $monarr = array('BETWEEN',array($monstart,$monend));
            $monumap['register_time'] = $monarr;//用户条件
            $monsmap['pay_time'] = $monarr;//充值条件
            $monpmap['create_time'] = $monarr;//渠道条件
            $mondmap['create_time'] = $monarr;//平台币条件
            $mongmap['create_time'] = $monarr;//游戏条件
            $info['user'] = $user->where($monumap)->count();//本月注册
            $info['game'] = $game->where($mongmap)->count();//本月新增游戏
            $samount = $spend->field('sum(pay_amount) as amount')->where($monsmap)->where("pay_status=1 and pay_way > 0")->find();//本月游戏充值 不包含平台币绑币
            if($samount['amount']){
                $info['samount']=$this->huanwei($samount['amount']);
            }else{
                $info['samount']=0;
            }
            $damount = $deposit->field('sum(pay_amount) as amount')->where($mondmap)->where("pay_status=1 and pay_way>0")->find();//本月平台币充值
            if($damount['amount']){
                $info['damount']=$damount['amount']==''?0:$damount['amount'];
            }else{
                $info['damount']=0;
            }
            $info['promote'] = $promote->where($monpmap)->count();
            $spmount = $spend
                            ->field('sum(pay_amount) as amount')
                            // ->join('tab_user on tab_user.id = tab_spend.user_id')
                            ->where($monsmap)
                            ->where("pay_status=1 and pay_way > 0 and promote_id > 0")
                            ->find();//本月渠道充值 不包含平台币绑币
            if($spmount['amount']){
                $info['spmount']=$this->huanwei($samount['amount']);
            }else{
                $info['spmount']=0;
            }
            //待办事项
            $this->daiban();
            //提示事项indexcontroller
            
            // 图表
            $info['pay']=$this->idata($this->linepay(),true,'pay_amount');
            $info['reg']=$this->idata($this->lineregister());
            $this->assign('info',$info);
            $this->display('info');
        }
    }
    private function daiban(){
        $user = M("User","tab_");
        $game = M("Game","tab_");
        $spend = M('Spend',"tab_");
        $deposit = M('Deposit',"tab_");
        $apply = M('Apply',"tab_");
        $applyapp = M('app_apply',"tab_");
        $promote = M("Promote","tab_");

        $pregist=$promote->where(array('status'=>0))->count();//渠道申请待审核数
        $daiban['pcount']=$pregist;

        //$map_appc['sdk_version'] = 1;
        $appc=$apply->where('ISNULL(pack_url)')->count();//渠道分包待打包数
        $daiban['appc']=$appc;

        $withc=M('Withdraw','tab_')->where(array('status'=>0,'promote_id'=>array('gt',0)))->count();//渠道提现待审核数
        $daiban['withc']=$withc;

        $spenc=$spend->where(array('pay_game_status'=>0,'pay_status'=>1))->count();//游戏充值待补单数
        $daiban['spenc']=$spenc;
        
        $applyapp=$applyapp->where(array('dow_url'=>''))->count();//APP分包待打包数
        $daiban['applyapp']=$applyapp;

        $msgc=M('Msg','tab_')->where(array('user_id'=>UID,'status'=>2))->count();//站内通知
        $daiban['msgc']=$msgc;

        $gameapply=M('Apply','tab_')->where(array('status'=>0))->count();//游戏申请
        $daiban['gameapply']=$gameapply;

        $domainapply=M('apply_union','tab_')->where(array('status'=>0))->count();//游戏申请
        $daiban['domainapply']=$domainapply;

        $this->assign('daiban',$daiban);
    }
    private function cate($name) {
        $cate = M("Category");
        $c = $cate->field('id')->where("status=1 and display=1 and name='$name'")->buildSql();
        $ca = $cate->field('id')->where("status=1 and display=1 and pid=$c")->select();
        foreach($ca as $c) {
            $d[]=$c['id'];
        }
        return "'".implode("','",$d)."'";
    }

    private function idata($data,$flag=false,$field='') {
        $day=array_flip($this->every_day(7));//七天日期
        $data=array_merge($day,$data);
        $d = $c = '';
        $max = 0;
        $min = 0;
        if (!empty($data)) {
            ksort($data);
            // $data = array_reverse($data);
            if ($flag) {
                foreach ($data as $k => $v) {
                    if (!empty($v)) {
                        foreach($v as $j => $u) {
                            $total += $u[$field];
                        }
                        $toto[]=$total;
                        
                    } else {
                        $toto[]=$total = 0;
                    }         
                    if ($min>$total){$min = $total;}
                    if ($max<$total){$max = $total;}
                    $c .= '"'.$k.'",';   
                    $total=0;       
                } 
                $d =implode(',', $toto).',';         
            } else {
                foreach ($data as $k => $v) {
                    $count = empty($v)?0:(is_array($v)?count($v):0); 
                    if ($min>$count){$min = $count;}
                    if ($max<$count){$max = $count;}
                    $d .= $count.',';
                    $c .= '"'.$k.'",';          
                }
            }
            $d = substr($d,0,-1);
            $c = substr($c,0,-1);           
        }
        $max++;
        $pay = array(
            'min' => $min,
            'max' => $max,
            'data' => $d,
            'cate' => $c
        );
        return $pay;
    }
    private function linepay() {
        $spend = M('Spend',"tab_");
        $deposit = M('Deposit',"tab_");
        $week = $this->total(9);
        $samount = $spend->field("pay_amount,pay_time as time")->where("pay_status=1 and pay_way>=0 and pay_time $week")->select();
        $damount = $deposit->field("pay_amount,create_time as time")->where("pay_status=1 and pay_way>=0 and create_time $week")->select();
        if (!empty($samount) && !empty($damount) )
            $data = array_merge($samount,$damount);
        else {
            if (!empty($samount))
                $data = $samount;
            else if (!empty($damount))
                $data = $damount;
            else 
                $data = '';
        }

        $result = array();
        $this->jump($data,$result,8);
        return $result;
    }
    
    private function lineregister() {
        $week = $this->total(9);
        $user = M("User","tab_")->field("account,register_time as time")->where("register_time $week")->select();

        if (!empty($user))
            $data = $user;
        else 
            $data = array(0,0,0,0,0,0,0);
        
        $result = array();
        $this->jump($data,$result,8);
        return $result;
    }
    
    protected function jump(&$a,&$b,$m,$n=0) {
        $num = count($a);
        if($m == 1) {
            return ;
        } else {
            $time = time();    
            if ($m < 8) {
                $c = 8 - $m;
                $time = $time - ($c * 86400);
            }
            $m -= 1;
            $t = date("Y-m-d",$time);
            if (empty($a) && count($b)<8) {
                $b[$t]= "";
            } else {
                foreach($a as $k => $g) {
                    $st = date("Y-m-d",$g['time']);
                    if($t===$st) {              
                        $b[$st][]=$g;
                        unset($a[$k]);
                    } else {
                        $b[$st]= "";
                    }
                }
                $a = array_values($a);      
            }
            return $this->jump($a,$b,$m,$num);
        } 
    }
    private function total($type) {
        switch ($type) {
            case 1: { // 今天
                $start=mktime(0,0,0,date('m'),date('d'),date('Y'));
                $end=mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;
            };break;
            case 3: { // 本月
                $start=mktime(0,0,0,date('m'),1,date('Y'));
                $end=mktime(0,0,0,date('m')+1,1,date('Y'))-1;
            };break;
            case 4: { // 本年
                $start=mktime(0,0,0,1,1,date('Y'));
                $end=mktime(0,0,0,1,1,date('Y')+1)-1;
            };break;
            case 5: { // 昨天
                $start=mktime(0,0,0,date('m'),date('d')-1,date('Y'));
                $end=mktime(0,0,0,date('m'),date('d'),date('Y'));
            };break;
            case 9: { // 前七天
                $start = mktime(0,0,0,date('m'),date('d')-6,date('Y'));
                $end=mktime(23,59,59,date('m'),date('d'),date('Y'));
            };break;
            default:
                $start='';$end='';
        }

        return " between $start and $end ";
    }
    //以当前日期 默认前七天 
    private function every_day($m=7){
        $time=array();
        for ($i=0; $i <$m ; $i++) { 
            $time[]=date('Y-m-d',mktime(0,0,0,date('m'),date('d')-$i,date('Y')));
        }
        return $time;
    }
    private function huanwei($total) {
        if(!strstr($total,'.')){
            $total=$total.'.00';
        }
        $total = empty($total)?'0':trim($total.' ');
        $zheng = ceil($total);
        $len = strlen($zheng);
        if ($len>8) { // 亿
           $len = $len-12;
           $total = $len>0?(round(($total/1e12),2).'万亿'):round(($total/1e8),2).'亿';            
        } else if ($len>4) { // 万
            $total = (round(($total/10000),2)).'w';
        }else if ($len>3) { // 千
            $total = (round(($total/1000),2)).'k';
        }
        return $total;
    }
}