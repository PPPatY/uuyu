<?php

namespace Admin\Controller;

use User\Api\UserApi as UserApi;

/**
 * 后台首页控制器
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
class StatController extends ThinkController
{

    /*
     * 根据时间计算注册人数
     */
    public function getcount($day, $n = null)
    {
        if (null !== $n) {
            $map['register_time'] = get_start_end_time($day, $n);
        } else {
            $map = get_last_day_time($day, "register_time");
        }
        if (isset($_REQUEST['promote_name'])) {
            if ($_REQUEST['promote_name'] == '全部') {
                unset($_REQUEST['promote_name']);
            } else if ($_REQUEST['promote_name'] == '自然注册') {
                $map['tab_user.promote_id'] = array("elt", 0);

            } else {
                $map['tab_user.promote_id'] = get_promote_id($_REQUEST['promote_name']);

            }
        }
        if (isset($_REQUEST['game_name'])) {
            if ($_REQUEST['game_name'] == '全部') {
                unset($_REQUEST['game_name']);
            } else {
                $map['tab_user.fgame_id'] = get_game_id($_REQUEST['game_name']);
            }
            $r_user_id = D('User')
                ->where($map)
                ->select();
            for ($i = 0; $i < count($r_user_id); $i++) {
                $sd[] = $r_user_id[$i]['user_id'];
            }
            $pid = implode(",", $sd);
            $count = D("User")
                ->where($map)
                ->count();
        } else {
            $r_user_id = D("User")->where($map)->select();
            for ($i = 0; $i < count($r_user_id); $i++) {
                $sd[] = $r_user_id[$i]['id'];
            }
            $pid = implode(",", $sd);
            $count = D("User")->where($map)->count();
        }
        $r_count = array($pid, $count);
        return $r_count;
    }

    /*
     * 计算留存率
     *
     */
    public function onelogincount($day, $count, $n = null)
    {
        if (null !== $n) {
            $onetime['login_time'] = get_start_end_time($day, $n);
        } else {
            $onetime = get_last_day_time($day, "login_time");
        }
        if (isset($_REQUEST['promote_name'])) {
            if ($_REQUEST['promote_name'] == '全部') {
                unset($_REQUEST['promote_name']);
            } else if ($_REQUEST['promote_name'] == '自然注册') {
                $onetime['promote_id'] = array("elt", 0);

            } else {
                $onetime['promote_id'] = get_promote_id($_REQUEST['promote_name']);

            }
        } else {
            $onetime['promote_id'] = array('egt', 0);
        }
        if (isset($_REQUEST['game_name'])) {
            if ($_REQUEST['game_name'] == '全部') {
                unset($_REQUEST['game_name']);
            } else {
                $onetime['game_id'] = get_game_id($_REQUEST['game_name']);
            }

        } else {
            $onetime['game_id'] = array('gt', 0);
        }
        $onetime['user_id'] = array('in', (string)$count[0]);
        $onelogincount = M("user_login_record", "tab_")->where($onetime)->count('distinct user_id');

        if ($onelogincount != 0) {
            if ($count[1] == 0) {
                $baifen = "";
            } else {
                $lu = $onelogincount / $count[1];
                $baifen = $lu * 100;
                $baifen = $baifen > 100 ? '100%' : $baifen . '%';
            }
        } else {

            if ($count[1] == 0) {
                $baifen = "";
            } elseif ($count[1] != 0) {
                $baifen = "0%";
            }

        }
        return round($baifen) . '%';

    }


//ARPU
//
    public function userarpu($p=0)
    {
        $event = A('Stat','Event');
        $event->userarpu($p);
    }
    public function userretention($p=0)
    {
        $event = A('Stat','Event');
        $event->userretention($p);
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
            $user=implode(',',array_column($user,'id'));
            $map['user_id']=array('in',$user);
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




//计算指定日期新用户数
    public function getnewcount($time)
    {
        $map = array();
        if (!empty($time)) {
            $map['register_time'] = get_start_end_time($_REQUEST['time-start']);
        } else {
            $map['register_time'] = -1;
        }
        if (isset($_REQUEST['promote_name'])) {
            if ($_REQUEST['promote_name'] == '全部') {
                unset($_REQUEST['promote_name']);
            } else if ($_REQUEST['promote_name'] == '自然注册') {
                $map['promote_id'] = array("elt", 0);

            } else {
                $map['promote_id'] = get_promote_id($_REQUEST['promote_name']);

            }
        } else {
            $map['promote_id'] = array('egt', 0);
        }
        if (isset($_REQUEST['game_name'])) {
            if ($_REQUEST['game_name'] == '全部') {
                unset($_REQUEST['game_name']);
            } else {
                $map['fgame_id'] = get_game_id($_REQUEST['game_name']);
            }
        } else {
            $map['fgame_id'] = array('gt', 0);
        }
        $r_user_id = M("User", "tab_")
            ->where($map)
            ->select();
        for ($i = 0; $i < count($r_user_id); $i++) {
            $sd[] = $r_user_id[$i]['id'];
        }
        $pid = implode(",", $sd);
        $count = M("User", "tab_")
            ->where($map)
            ->count();
        $count = array($pid, $count);
        return $count;

    }

//计算留存数
    public function getplaycount($day, $count, $n = null)
    {
        if (null !== $n) {
            $onetime['login_time'] = get_start_end_time($day, $n);
        } else {
            $onetime = get_last_day_time($day, "login_time");
        }
        if (isset($_REQUEST['promote_name'])) {
            if ($_REQUEST['promote_name'] == '全部') {
                unset($_REQUEST['promote_name']);
            } else if ($_REQUEST['promote_name'] == '自然注册') {
                $onetime['promote_id'] = array("elt", 0);
            } else {
                $onetime['promote_id'] = get_promote_id($_REQUEST['promote_name']);
            }
        } else {
            $onetime['promote_id'] = array('egt', 0);
        }
        if (isset($_REQUEST['game_name'])) {
            if ($_REQUEST['game_name'] == '全部') {
                unset($_REQUEST['game_name']);
            } else {
                $onetime['game_id'] = get_game_id($_REQUEST['game_name']);
            }
        } else {
            $onetime['game_id'] = array('gt', 0);
        }
        $onetime['user_id'] = array('in', (string)$count[0]);
        $onelogincount = M("user_login_record", "tab_")->where($onetime)->count('distinct user_id');
        return $onelogincount;
    }

//计算次日留存率
    function get_cilogin($newcount, $cicount)
    {
        if ($cicount == 0) {
            return sprintf("%.2f", 0) . '%';
        } else {
            return round($cicount / $newcount * 100) . '%';
        }
    }

//计算指定游戏 用户总数 与时间无关
    public function getallcount()
    {
        $map = array();
        if (!isset($_REQUEST['time-start'])) {
            $map['a.register_time'] = -1;
        }
        if (isset($_REQUEST['game_name'])) {
            if ($_REQUEST['game_name'] == '全部') {
                unset($_REQUEST['game_name']);
            } else {
                $map['a.fgame_id'] = get_game_id($_REQUEST['game_name']);
            }
        } else {
            $map['a.fgame_id'] = array('gt', 0);
        }
        if (isset($_REQUEST['promote_name'])) {
            if ($_REQUEST['promote_name'] == '全部') {
                unset($_REQUEST['promote_name']);
            } else if ($_REQUEST['promote_name'] == '自然注册') {
                $map['a.promote_id'] = array("elt", 0);
            } else {
                $map['a.promote_id'] = get_promote_id($_REQUEST['promote_name']);
            }
        } else {
            $map['a.promote_id'] = array('egt', 0);
        }
        $count = M("User as a", "tab_")
            ->field("count(*) as count")
            ->where($map)
            ->count();
        return $count;
    }

//计算付费用户数
    public function getpaycount()
    {
        $count = 0;
        if (isset($_REQUEST['time-start'])) {
            $map['pay_time'] = array("lt", strtotime($_REQUEST['time-start']) + (60 * 60 * 24));
        } else {
            $map['pay_time'] = -1;
        }
        $map['pay_status'] = 1;
        if (isset($_REQUEST['game_name'])) {
            if ($_REQUEST['game_name'] == '全部') {
                unset($_REQUEST['game_name']);
            } else {
                $map['game_id'] = get_game_id($_REQUEST['game_name']);
            }
        } else {
            $map['game_id'] = array('gt', 0);
        }
        if (isset($_REQUEST['promote_name'])) {
            if ($_REQUEST['promote_name'] == '全部') {
                unset($_REQUEST['promote_name']);
            } else if ($_REQUEST['promote_name'] == '自然注册') {
                $map['promote_id'] = array("elt", 0);
            } else {
                $map['promote_id'] = get_promote_id($_REQUEST['promote_name']);
            }
        } else {
            $map['promote_id'] = array('egt', 0);
        }
        $count = M("spend", "tab_")
            ->where($map)
            ->count('distinct id');
        return $count;
    }

//计算新用户付费金额
    public function getnewpaycount()
    {
        $count = 0;
        if (isset($_REQUEST['time-start'])) {
            $map['pay_time'] = array("lt", strtotime($_REQUEST['time-start']) + (60 * 60 * 24));
            $newuser = $this->getnewcount($_REQUEST['time-start']);
            $map['user_id'] = array('in', (string)$newuser[0]);
        } else {
            $map['pay_time'] = -1;
        }
        $map['pay_status'] = 1;
        if (isset($_REQUEST['game_name'])) {
            if ($_REQUEST['game_name'] == '全部') {
                unset($_REQUEST['game_name']);
            } else {
                $map['a.fgame_id'] = get_game_id($_REQUEST['game_name']);
            }
        } else {
            $map['a.fgame_id'] = array('gt', 0);
        }
        if (isset($_REQUEST['promote_name'])) {
            if ($_REQUEST['promote_name'] == '全部') {
                unset($_REQUEST['promote_name']);
            } else if ($_REQUEST['promote_name'] == '自然注册') {
                $map['a.promote_id'] = array("elt", 0);
            } else {
                $map['a.promote_id'] = get_promote_id($_REQUEST['promote_name']);
            }
        } else {
            $map['a.promote_id'] = array('egt', 0);
        }
        $list = M("User as a", "tab_")
            ->field("sum(pay_amount) as sum")
            ->join("tab_spend as c on c.game_id=a.fgame_id")
            ->where($map)
            ->group('a.id')
            ->find();
        if (!empty($list['sum'])) {
            $count = $list['sum'];
        }
        return sprintf("%.2f", $count);
    }

//计算总付费金额
    public function getallpaycount()
    {
        $count = 0;
        $map = array();
        if (!isset($_REQUEST['time-start'])) {
            $map['pay_time'] = -1;
        }
        $map['pay_status'] = 1;
        if (isset($_REQUEST['game_name'])) {
            if ($_REQUEST['game_name'] == '全部') {
                unset($_REQUEST['game_name']);
            } else {
                $map['game_id'] = get_game_id($_REQUEST['game_name']);
            }
        } else {
            $map['game_id'] = array('gt', 0);
        }
        if (isset($_REQUEST['promote_name'])) {
            if ($_REQUEST['promote_name'] == '全部') {
                unset($_REQUEST['promote_name']);
            } else if ($_REQUEST['promote_name'] == '自然注册') {
                $map['promote_id'] = array("elt", 0);
            } else {
                $map['promote_id'] = get_promote_id($_REQUEST['promote_name']);
            }
        } else {
            $map['promote_id'] = array('egt', 0);
        }
        $list = M("spend", "tab_")
            ->field("sum(pay_amount) as sum")
            ->where($map)
            ->find();
        if (!empty($list['sum'])) {
            $count = $list['sum'];
        }
        return sprintf("%.2f", $count);
    }

//计算总付费率
    public function getrate()
    {
        $pr = $this->getpaycount();//总付费人数
        $all = $this->getallcount();
        if ($all == 0) {
            $count = 0;
        } else {
            $count = $pr / $all;
            $count = $count > 1 ? 100 : $count * 100;
        }
        return sprintf("%.2f", $count) . '%';
    }

// 计算活跃用户数(当前日期所在一周的时间)
    public function gethuocount()
    {
        $count = 0;
        if (isset($_REQUEST['game_name']) || isset($_REQUEST['time-start'])) {
            if (isset($_REQUEST['game_name'])) {
                if ($_REQUEST['game_name'] == '全部') {
                    unset($_REQUEST['game_name']);
                } else {
                    $map['game_id'] = $_REQUEST['game_id'];
                    $time = date("Y-m-d", time());
                    $start = strtotime("$time - 6 days");
                    //周末
                    $end = strtotime("$time");
                    $map['login_time'] = array("between", array($start, $end));
                }
            }
            if (isset($_REQUEST['time-start'])) {
                $time2 = $_REQUEST['time-start'];
                $start2 = strtotime("$time2 - 6 days");
                //周末
                $end2 = strtotime("$time2");
                $map['login_time'] = array("between", array($start2, $end2));
            }
            $data = M("user_login_record", "tab_")
                ->group('user_id')
                ->having('count(user_id) > 2')
                ->where($map)
                ->select();
            $count = count($data);
        }
        return sprintf("%.2f", $count);
    }

//获取用户ARPU
    public function getuserarpu()
    {
        $new = $this->getnewpaycount();
        if (isset($_REQUEST['time-start'])) {
            $newcount = end($this->getnewcount($_REQUEST['time-start']));
            if ($newcount == 0) {
                $count = 0;
            } else {
                $count = $new / $newcount;
            }
        } else {
            $count = 0;
        }
        return sprintf("%.2f", $count);

    }

// 获取活跃ARPU
    public function gethuoarpu()
    {
        if (isset($_REQUEST['game_name']) || isset($_REQUEST['time-start'])) {
            if (isset($_REQUEST['game_name'])) {
                if ($_REQUEST['game_name'] == '全部') {
                    unset($_REQUEST['game_name']);
                } else {
                    $map['tab_user_login_record.game_id'] = get_game_id($_REQUEST['game_name']);
                    $time = date("Y-m-d", time());
                    $start = strtotime("$time - 6 days");
                    //周末
                    $end = strtotime("$time");
                    $map['login_time'] = array("between", array($start, $end));
                }
            }
            if (isset($_REQUEST['time-start'])) {
                $time2 = $_REQUEST['time-start'];
                $start2 = strtotime("$time2 - 6 days");
                //周末
                $end2 = strtotime("$time2");
                $map['login_time'] = array("between", array($start2, $end2));
            }
            $data = M("user_login_record", "tab_")
                ->group('user_id')
                ->having('count(user_id) > 2')
                ->where($map)
                ->select();
            foreach ($data as $key => $value) {
                $data1[] = $value['user_id'];
            }
            foreach ($data1 as $value) {
                $user_account[] = get_user_account($value);
            }
            $pid = implode(',', $user_account);
        }
        $map['user_account'] = array('in', $pid);
        if ($pid != '') {
            $huosum = M("spend ", "tab_")
                ->distinct(true)
                ->field("pay_amount")
                ->join("tab_user_login_record on tab_spend.game_id = tab_user_login_record.game_id")
                ->where($map)
                ->select();
            foreach ($huosum as $value) {
                $huosum2[] = $value['pay_amount'];
            }
            $sum = array_sum($huosum2);
            $count = count($data);
            $return = $sum / $count;
        } else {
            $return = 0;
        }

        return $return;

    }

//获取付费ARPU
    public function getpayarpu()
    {
        $paysum = $this->getallpaycount();//所有用户付费
        $paycount = $this->getpaycount();//新用户付费
        if ($paycount != 0) {
            $count = $paysum / $paycount;
        } else {
            $count = 0;
        }
        return sprintf("%.2f", $count);
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
            //新付费玩家
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
    function game_analysis(){
        if($_REQUEST['time-start']!=''&&$_REQUEST['time-end']!=''){
            $start=$_REQUEST['time-start'];
            $end=$_REQUEST['time-end'];
        }else{
            $start=get_lastweek_name(6);
            $end=date('Y-m-d');
        }
        $umap['register_time']=array('BETWEEN',array(strtotime($start),strtotime($end)+24*60*60-1));
        $smap['pay_time']=array('BETWEEN',array(strtotime($start),strtotime($end)+24*60*60-1));
        if($_REQUEST['promote_id']!=''){
            $umap['promote_id']=$_REQUEST['promote_id'];
            $smap['promote_id']=$_REQUEST['promote_id'];
        }
        $data=M('Game','tab_')->field('id as game_id, game_name')->order('id desc')->select();
        foreach ($data as $key => $value) {
            $umap['fgame_id']=$value['game_id'];
            $smap['game_id']=$value['game_id'];
            $udata=M('User','tab_')
            ->field('count(id) as register_num')
            ->where($umap)
            ->find();
            $data[$key]['count']=$udata['register_num'];
            
            $smap['pay_status']=1;
            $sdata=M('Spend','tab_')
                ->field('ifnull(sum(pay_amount),0) as sum')
                ->where($smap)
                ->find();
            $data[$key]['sum']=$sdata['sum'];
        }
        if($_REQUEST['data_order']==2){
            $data_order_type='sum';
            $data_order=3;//倒序
        }else{
            $data_order_type='count';
            $data_order=3;
        }
        $data=my_sort($data,$data_order_type,(int)$data_order);
        $data = array_slice($data, 0, 12);
        // 前台显示
        // X轴游戏
            $xAxis="[";
            foreach ($data as $tk => $tv) {
                $xAxis.="'".$tv['game_name']."',";
            }
            $xAxis.="]";
        //x轴注册数据
        $xzdate="[";
        foreach ($data as $key => $value) {
            $xzdate.="'".$value['count']."',";
        }
        $xzdate.="]";
        //x轴充值数据
        $xsdate="[";
        foreach ($data as $key => $value) {
            $xsdate.="'".$value['sum']."',";
        }
        $xsdate.="]";
        $this->assign('xzdate',$xzdate);
        $this->assign('xsdate',$xsdate);
        $this->assign('xAxis',$xAxis);
        $this->assign('start',$start);
        $this->assign('end',$end);
        $this->assign('data',$data);
        $this->display();
    }
    function promote_analysis(){
    	
        if($_REQUEST['time-start']!=''&&$_REQUEST['time-end']!=''){
            $start=$_REQUEST['time-start'];
            $end=$_REQUEST['time-end'];
        }else{
            $start=get_lastweek_name(6);
            $end=date('Y-m-d');
        }
        $umap['register_time']=array('BETWEEN',array(strtotime($start),strtotime($end)+24*60*60-1));
        $smap['pay_time']=array('BETWEEN',array(strtotime($start),strtotime($end)+24*60*60-1));
        if($_REQUEST['game_id']!=''){
            $umap['fgame_id']=$_REQUEST['game_id'];
            $smap['game_id']=$_REQUEST['game_id'];
        }
        $data=M('Promote','tab_')->field('id as promote_id,account as promote_name')->order('id desc')->select();
        foreach ($data as $key => $value) {
            $umap['promote_id']=$value['promote_id'];
            $smap['promote_id']=$value['promote_id'];
            $udata=M('User','tab_')
            ->field('count(id) as register_num')
            ->where($umap)
            ->find();
            $data[$key]['count']=$udata['register_num'];
            $smap['pay_status']=1;
            $sdata=M('Spend','tab_')
                ->field('ifnull(sum(pay_amount),0) as sum')
                ->where($smap)
                ->find();
            $data[$key]['sum']=$sdata['sum'];
        }
        if($_REQUEST['data_order']==2){
            $data_order_type='sum';
            $data_order=3;//倒序
        }else{
            $data_order_type='count';
            $data_order=3;
        }
        $data=my_sort($data,$data_order_type,(int)$data_order);
        $data = array_slice($data, 0, 12);
        // 前台显示
        // X轴游戏
            $xAxis="[";
            foreach ($data as $tk => $tv) {
                $xAxis.="'".$tv['promote_name']."',";
            }
            $xAxis.="]";
        //x轴注册数据
        $xzdate="[";
        foreach ($data as $key => $value) {
            $xzdate.="'".$value['count']."',";
        }
        $xzdate.="]";
        //x轴充值数据
        $xsdate="[";
        foreach ($data as $key => $value) {
            $xsdate.="'".$value['sum']."',";
        }
        $xsdate.="]";
        $this->assign('xzdate',$xzdate);
        $this->assign('xsdate',$xsdate);
        $this->assign('xAxis',$xAxis);
        $this->assign('start',$start);
        $this->assign('end',$end);
        $this->assign('data',$data);
        $this->display();
    }
    /**
     * 流失分析
     */
    public function userloss(){
        $start=get_lastweek_name(6);
        $end=date('Y-m-d');
        $this->assign('start',$start);
        $this->assign('end',$end);
        $event = A('Stat','Event');
        $result=$event->loss_pic($_REQUEST);
        foreach ($result['day'] as $key => $value) {
            $res['lossplayer']['loss'][$value]=$result['loss_count'][$key];
            $res['lossplayer']['lossrate'][$value]=$result['loss_rate'][$key];
        }
        $money_arr=array(
            ">$2000","$1000~2000","$600~1000","$200~600","$100~200","$40~100","$20~40","$10~20","$2~$10","<$2",
        );
        foreach ($money_arr as $key => $value) {
            $res['lossmoney'][$value]=$result['loss_money'][$key];
        }
        $times_arr=array(
            ">50次","41~50次","31~40次","21~30次","11~20次","6~10次","5次","4次","3次","2次","1次","未付费",
        );
        foreach ($times_arr as $key => $value) {
            $res['losstimes'][$value]=$result['loss_times'][$key];
        }
        $this->assign("json_data",json_encode($res));
        $this->display();
    }
}
