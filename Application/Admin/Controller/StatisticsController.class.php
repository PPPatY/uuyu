<?php

namespace Admin\Controller;
use User\Api\UserApi as UserApi;

/**
 * 后台首页控制器
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
class StatisticsController extends ThinkController {
	// const model_name = 'Spend';
    public function overview(){
        $this->assign('openegretmain','openegretmain');//模板还样式使用
        //定义表名
        $user = M("User","tab_");
        $userlogin = M("user_login_record","tab_");
        $game = M("Game","tab_");
        $spend = M('Spend',"tab_");
        $deposit = M('Deposit',"tab_");
        $promote = M("Promote","tab_");
        $game = M("Game","tab_");
        $gamesource = M("Game_source","tab_");
        if(I('isbd')==1){
            $isbdpw['pay_way'] = array('neq',-1);
        }else{
            $isbdpw['id'] = array('gt',0);
        }
        //平台数据概况
        $platform_data['all_user']=$user->count();//累计注册玩家人数
        //两表并集 
        $afufei=$spend
                ->field('user_id')
                ->where(array('pay_status'=>1))
                ->where($isbdpw)
                ->group('user_id')
                ->select();
        $platform_data['all_pay_user']=count($afufei);//累计付费玩家人数不包括平台币

        $spay=$spend->where(array('pay_status'=>1))->where($isbdpw)->sum('pay_amount');
        $platform_data['all_pay']=$this->huanwei($spay);//累计流水

        $platform_data['all_promote']=$promote->count();//累计渠道

        $platform_data['all_game']=$game->count();//累计游戏

        $platform_data['all_zuser']=$user->where(array('promote_id'=>array('eq',0)))->count();//自然注册玩家

        $zspay=$spend->where(array('promote_id'=>array('eq',0)))->where(array('pay_status'=>1))->where($isbdpw)->sum('pay_amount');//自然总流水

        $platform_data['all_zpay']=$this->huanwei($zspay);//累计渠道充值玩家

        $platform_data['all_tuser']=$user->where(array('promote_id'=>array('gt',0)))->count();//累计渠道注册玩家

        $tspay=$spend->where(array('promote_id'=>array('gt',0)))->where($isbdpw)->where(array('pay_status'=>1))->sum('pay_amount');//累计渠道充值玩家


        $platform_data['all_tpay']=$this->huanwei($tspay);//累计渠道充值玩家

        $this->assign('platform_data',$platform_data);

        //实时数据概况
        $today = $this->total(1);
        $thisweek = $this->total(2);
        $thismounth = $this->total(3);
        //注册
        $realtime_data['today_user']=$user->where(array('register_time'.$today))->count();//今日注册

        $realtime_data['thisweek_user']=$user->where(array('register_time'.$thisweek))->count();//本周注册

        $realtime_data['thismounth_user']=$user->where(array('register_time'.$thismounth))->count();//本月注册

        //今日活跃
        $mod = array(
            'alias'=>'u',
            'field'=>'user_id',
            'join'=>'tab_user_login_record as r on u.id = r.user_id',
            'where'=>'r.login_time'.$today,
            'group'=>'user_id',
            );
        $tlogin=$this->joindata(D('User'),$mod);
        //本周活跃
        $mod['where'] = 'r.login_time'.$thisweek;
        $wlogin=$this->joindata(D('User'),$mod);
        //本月活跃
        $mod['where'] = 'r.login_time'.$thismounth;
        $mlogin=$this->joindata(D('User'),$mod);
        $realtime_data['today_active']=count($tlogin);
        $realtime_data['thisweek_active']=count($wlogin);
        $realtime_data['thismounth_active']=count($mlogin);
        //充值
        //今日流水
        $todayspay=$spend->where(array('pay_time'.$today))->where(array('pay_status'=>1))->where($isbdpw)->sum('pay_amount');
        $realtime_data['today_pay']=$this->huanwei($todayspay);
        //本周流水
        $weekspay=$spend->where(array('pay_time'.$thisweek))->where(array('pay_status'=>1))->where($isbdpw)->sum('pay_amount');
        $realtime_data['thisweek_pay']=$this->huanwei($weekspay);
        //本月流水
        $mounthspay=$spend->where(array('pay_time'.$thismounth))->where(array('pay_status'=>1))->where($isbdpw)->sum('pay_amount');
        $realtime_data['thismounth_pay']=$this->huanwei($mounthspay);
        // var_dump($realtime_data);exit;
        $this->assign('realtime_data',$realtime_data);


        //排行
        $yesterday=$this->total(5);
        $lastweek=$this->total(6);
        $lastmounth=$this->total(7);
        $type=$_REQUEST['type'];
        $event = A('Statistics','Event');
        if($type==1 || $type==''){
            $list_data=$event->data_order($today,$yesterday);
        }elseif($type==2){
            $list_data=$event->data_order($thisweek,$lastweek);
        }elseif($type==3){
            $list_data=$event->data_order($thismounth,$lastmounth);
        }
        $this->assign('zhuce',$list_data['zhuce']);
        $this->assign('active',$list_data['active']);
        $this->assign('pay',$list_data['pay']);
    	$this->display();
    }

    public function zhexian(){
        if(I('isbd')==1){
            $isbdpw['pay_way'] = array('neq',-1);
        }else{
            $isbdpw['id'] = array('gt',0);
        }
        $day=$this->every_day(7);
        $time=$this->total(9);
        $key=$_REQUEST['key'];
        $user = M("User","tab_");
        $userplay = M("UserPlay","tab_");
        $spend = M('Spend',"tab_");
        $deposit = M('Deposit','tab_');
        if($key==1){
            //注册数据
           $minuserplay = $userplay
                            ->field('tab_user_play.*,min(create_time)')
                            ->join('tab_user on tab_user_play.user_id = tab_user.id')
                            ->group('game_id')
                            ->buildsql();
            $data=M()->query("select mm.game_id,mm.game_name,date_format(FROM_UNIXTIME(create_time),'%Y-%m-%d') AS time,count(mm.user_id) as cg from {$minuserplay} mm where mm.create_time {$time} and mm.game_id > 0  group by mm.game_id,time order by cg desc limit 0,5");
            $title=M()->query("select mm.game_id,mm.game_name,date_format(FROM_UNIXTIME(create_time),'%Y-%m-%d') AS time,count(mm.user_id) as cg from {$minuserplay} mm where mm.create_time {$time} and mm.game_id > 0  group by mm.game_id order by cg desc limit 0,5");
            $title=array_slice($data,0,5);
            $title=array_column($title,'game_name');
            $data=array_group_by($data,'time');
            foreach ($day as $key => $value) {
                if(array_key_exists($value, $data)){
                    // var_dump($data['2016-12-10']);exit;
                    foreach ($data[$value] as $kk => $vv) {
                        $game_name=$vv['game_name'];
                        $dayy[$value][$game_name]=$vv['cg'];
                    }
                }
            }
        }elseif($key==2){
            //活跃数据
            $active_sql1=$user
                            ->field('ur.*,min(ur.login_time)')
                            ->join('tab_user_login_record ur on ur.user_id = tab_user.id')
                            ->where(array('ur.game_id'=>array('gt',0)))
                            ->group('game_id,user_id')
                            ->buildsql();
            $data=M()->query("select mm.game_id,mm.game_name,date_format(FROM_UNIXTIME(login_time),'%Y-%m-%d') AS time,count(mm.user_id) as cg from {$active_sql1} mm where mm.login_time {$time} and mm.game_id > 0  group by time, mm.game_id order by cg desc limit 0,5");

            $title=M()->query("select mm.game_id,mm.game_name,date_format(FROM_UNIXTIME(login_time),'%Y-%m-%d') AS time,count(mm.user_id) as cg from {$active_sql1} mm where mm.login_time {$time} and mm.game_id > 0  group by mm.game_id order by cg desc limit 0,5");
            $title=array_slice($data,0,5);
            $title=array_column($title,'game_name');
            $data=array_group_by($data,'time');
            foreach ($day as $key => $value) {
                if(array_key_exists($value, $data)){
                    foreach ($data[$value] as $kk => $vv) {
                        $game_name=$vv['game_name'];
                        $dayy[$value][$game_name]=$vv['cg'];
                    }
                }
            }
        }elseif($key==3){
            //充值数据
            $data=$spend->field('game_id,game_name,date_format(FROM_UNIXTIME( pay_time),"%Y-%m-%d") AS time,sum(pay_amount) as cg')->where(array('pay_time'.$nowtime))->where(array('game_id'=>array('gt',0)))->where(array('pay_status'=>1))->where($isbdpw)->group('time,game_id')->order('cg desc')->limit(5)->select();
            $title=$spend->field('game_name,sum(pay_amount) as cg')->where(array('pay_time'.$nowtime))->where(array('game_id'=>array('gt',0)))->where(array('pay_status'=>1))->where($isbdpw)->group('game_id')->order('cg desc')->limit(5)->select();
            $title=array_column($title,'game_name');
            $data=array_group_by($data,'time');
            foreach ($day as $key => $value) {
                if(array_key_exists($value, $data)){
                    foreach ($data[$value] as $kk => $vv) {
                        $game_name=$vv['game_name'];
                        $dayy[$value][$game_name]=$vv['cg'];
                    }
                }
            }
        }
        
        $this->assign('day0',$day[0]);
        $this->assign('day1',$day[1]);
        $this->assign('day2',$day[2]);
        $this->assign('day3',$day[3]);
        $this->assign('day4',$day[4]);
        $this->assign('day5',$day[5]);
        $this->assign('day6',$day[6]);
        $this->assign('dayy',$dayy);
        $this->assign('title1',$title[1]);
        $this->assign('title0',$title[0]);
        $this->assign('title2',$title[2]);
        $this->assign('title3',$title[3]);
        $this->assign('title4',$title[4]);
        $this->display();
    }

    //数据概况
    public function data_profile(){
        $keytype=$_REQUEST['key']==""?1:$_REQUEST['key'];
        if(I('isbd')==1){
            $isbdpw['pay_way'] = array('neq',-1);
        }else{
            $isbdpw['id'] = array('gt',0);
        }
        $user=M('User','tab_');
        $spend=M('Spend','tab_');
        $deposit= M('Deposit','tab_');
        if($keytype==1){
            $time=$this->time2other();
            $tt=$this->total(1);
            //注册数据
            $udata=$user->field('date_format(FROM_UNIXTIME( register_time),"%H") AS time,count(id) as count')->where('register_time'.$tt)->group('time')->select();
            $xtime=$this->for_every_time_point($time,$udata,'time','count');

            //充值数据
            //spend
            $sdata=$spend->field('date_format(FROM_UNIXTIME( pay_time),"%H") AS time,sum(pay_amount) as sum')->where('pay_time'.$tt)->where(array('pay_status'=>1))->where($isbdpw)->group('time')->select();
            $xstime=$this->for_every_time_point($time,$sdata,'time','sum');
            $xdtime=$this->for_every_time_point($time,$ddata,'time','sum');
            foreach ($xstime as $key => $value) {
                $stime[$key]['sum']=$value['sum']+$xdtime[$key]['sum'];
            }
        }elseif($keytype==2){
        	//7天
            $time=$this->time2other('7day');
            $tt=$this->total(9);
            //注册数据
            $udata=$user->field('date_format(FROM_UNIXTIME( `register_time`),"%Y-%m-%d") AS time,count(id) as count')->where(array('register_time'.$tt))->where(array('fgame_id'=>array('gt',0)))->group('time')->order('time asc')->select();
            $xtime=$this->for_every_time_point($time,$udata,'time','count');

            //充值数据
            //spend
            $sdata=$spend->field('date_format(FROM_UNIXTIME( pay_time),"%Y-%m-%d") AS time,sum(pay_amount) as sum')->where(array('pay_time'.$tt))->where(array('game_id'=>array('gt',0)))->where(array('pay_status'=>1))->where($isbdpw)->group('time')->order('time asc')->select();
            $xstime=$this->for_every_time_point($time,$sdata,'time','sum');
            $xdtime=$this->for_every_time_point($time,$ddata,'time','sum');
            foreach ($xstime as $key => $value) {
                $stime[$key]['sum']=$value['sum']+$xdtime[$key]['sum'];
            }        
        }elseif($keytype==3){//30天
            $time=$this->time2other('30day');
            $tt=$this->total(10);
            //注册数据
            $udata=$user->field('date_format(FROM_UNIXTIME( `register_time`),"%Y-%m-%d") AS time,count(id) as count')->where(array('register_time'.$tt))->where(array('fgame_id'=>array('gt',0)))->group('time')->order('time asc')->select();
            $xtime=$this->for_every_time_point($time,$udata,'time','count');

            //充值数据
            //spend
            $sdata=$spend->field('date_format(FROM_UNIXTIME( pay_time),"%Y-%m-%d") AS time,sum(pay_amount) as sum')->where(array('pay_time'.$tt))->where(array('game_id'=>array('gt',0)))->where(array('pay_status'=>1))->where($isbdpw)->group('time')->order('time asc')->select();
            $xstime=$this->for_every_time_point($time,$sdata,'time','sum');
            $xdtime=$this->for_every_time_point($time,$ddata,'time','sum');
            foreach ($xstime as $key => $value) {
                $stime[$key]['sum']=$value['sum']+$xdtime[$key]['sum'];
            }        
        }elseif($keytype==4){//1年
            $time=$this->time2other('12mounth');
            $tt=$this->total(8);
            //注册数据
            $udata=$user->field('date_format(FROM_UNIXTIME( `register_time`),"%Y-%m") AS time,count(id) as count')->where(array('register_time'.$tt))->where(array('fgame_id'=>array('gt',0)))->group('time')->order('time asc')->select();
            $xtime=$this->for_every_time_point($time,$udata,'time','count');

            //充值数据
            //spend
            $sdata=$spend->field('date_format(FROM_UNIXTIME( pay_time),"%Y-%m") AS time,sum(pay_amount) as sum')->where(array('pay_time'.$tt))->where(array('game_id'=>array('gt',0)))->where(array('pay_status'=>1))->where($isbdpw)->group('time')->order('time asc')->select();
            $xstime=$this->for_every_time_point($time,$sdata,'time','sum');
            $xdtime=$this->for_every_time_point($time,$ddata,'time','sum');
            foreach ($xstime as $key => $value) {
                $stime[$key]['sum']=$value['sum']+$xdtime[$key]['sum'];
            }
        }
        // 前台显示
        // X轴日期
        if($keytype==1){
            $xAxis="[";
            foreach ($time as $tk => $tv) {
                $xAxis.="'".$tk.":00',";
            }
            $xAxis.="]";
        }elseif($keytype==2){
            sort($time);
            $xAxis="[";
            foreach ($time as $tk => $tv) {
                $xAxis.="'".$tv."',";
            }
            $xAxis.="]";
        }elseif($keytype==3){
            sort($time);
            $xAxis="[";
            foreach ($time as $tk => $tv) {
                $xAxis.="'".$tv."',";
            }
            $xAxis.="]";
        }elseif($keytype==4){
            sort($time);
            $xAxis="[";
            foreach ($time as $tk => $tv) {
                $xAxis.="'".$tv."',";
            }
            $xAxis.="]";
        }
        //x轴注册数据
        $xzdate="[";
        foreach ($xtime as $key => $value) {
            $xzdate.="'".$value['count']."',";
        }
        $xzdate.="]";
        //x轴充值数据
        $xsdate="[";
        foreach ($stime as $key => $value) {
            $xsdate.="'".$value['sum']."',";
        }
        $xsdate.="]";
        $this->assign('xzdate',$xzdate);
        $this->assign('xsdate',$xsdate);
        $this->assign('xAxis',$xAxis);
        $this->assign('qingxie',count($time));
        $this->display();
    }
    /**
     * [数据折线 分配每个时间段]
     * @param  [type] $time [时间点]
     * @return [type]       [description]
     */
    private function for_every_time_point($time,$data,$key1,$key2){
        foreach ($time as $key => $value) {
            $newdata[$key][$key2]=0;
            foreach ($data as $k => $v) {
                if($v[$key1]==$key){
                    $newdata[$key][$key2]=$v[$key2];
                }
            }
        }
        return $newdata;
    }
    //把时间戳 当前时间一天分成24小时  前七天 前30天  前12个月
    function time2other($type='day'){
        if($type=='day'){//一天分成24小时
            $start = mktime(0,0,0,date("m"),date("d"),date("y"));
            for($i = 0; $i < 24; $i++){
                static $x=0;
                $xx=$x++;
                if($xx<10){
                    $xxx='0'.$xx;
                }else{
                    $xxx=$xx;
                }
                
                $b = $start + ($i * 3600);
                $e = $start + (($i+1) * 3600)-1;
                $time[$xxx]="between $b and $e";
            }
        }
        if($type=='7day'){
            $ttime=array_reverse($this->every_day());
            foreach ($ttime as $key => $value) {
                $time[$value]=$value;
            }
        }
        if($type=='30day'){
            $ttime=array_reverse($this->every_day(30));
            foreach ($ttime as $key => $value) {
                $time[$value]=$value;
            }
        }
        if($type=='12mounth'){
            $ttime=array_reverse(before_mounth());
            foreach ($ttime as $key => $value) {
                $time[$value]=$value;
            }
        }
        
        return $time;
    }
    //以当前日期 默认前七天 
    private function every_day($m=7){
        $time=array();
        for ($i=0; $i <$m ; $i++) { 
            $time[]=date('Y-m-d',mktime(0,0,0,date('m'),date('d')-$i,date('Y')));
        }
        return $time;
    }
    private function total($type) {
        switch ($type) {
            case 1: { // 今天
                $start=mktime(0,0,0,date('m'),date('d'),date('Y'));
                $end=mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;
            };break;
             case 2: { // 本周
            //当前日期
            $sdefaultDate = date("Y-m-d");
            //$first =1 表示每周星期一为开始日期 0表示每周日为开始日期
            $first=1;
            //获取当前周的第几天 周日是 0 周一到周六是 1 - 6
            $w=date('w',strtotime($sdefaultDate));
            //获取本周开始日期，如果$w是0，则表示周日，减去 6 天
            $week_start=date('Y-m-d',strtotime("$sdefaultDate -".($w ? $w - $first : 6).' days'));
            //本周结束日期
            $week_end=date('Y-m-d',strtotime("$week_start +6 days"));
                        //当前日期
            $sdefaultDate = date("Y-m-d");
            //$first =1 表示每周星期一为开始日期 0表示每周日为开始日期
            $first=1;
            //获取当前周的第几天 周日是 0 周一到周六是 1 - 6
            $w=date('w',strtotime($sdefaultDate));
            //获取本周开始日期，如果$w是0，则表示周日，减去 6 天
            $start=strtotime("$sdefaultDate -".($w ? $w - $first : 6).' days');
            //本周结束日期
            $end=$start+7*24*60*60-1;
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
                $end=mktime(0,0,0,date('m'),date('d'),date('Y'))-1;
            };break;
            case 6: { // 上周
                $start=mktime(0, 0 , 0,date("m"),date("d")-date("w")+1-7,date("Y"));
                $end=mktime(23,59,59,date("m"),date("d")-date("w")+7-7,date("Y"));
            };break;
            case 7: { // 上月
                $start=mktime(0, 0 , 0,date("m")-1,1,date("Y"));
                $end=mktime(23,59,59,date("m") ,0,date("Y"));
            };break;
            case 8: { // 上一年
                $start=mktime(0,0,0,date('m')-11,1,date('Y'));
                $end=mktime(0,0,0,date('m')+1,1,date('Y'))-1;
            };break;
            case 9: { // 前七天
                $start = mktime(0,0,0,date('m'),date('d')-6,date('Y'));
                $end=mktime(23,59,59,date('m'),date('d'),date('Y'));
            };break;
            case 10: { // 前30天
                $start = mktime(0,0,0,date('m'),date('d')-29,date('Y'));
                $end=mktime(23,59,59,date('m'),date('d'),date('Y'));
            };break;
            default:
                $start='';$end='';
        }
        return " between $start and $end ";
    }
    private function huanwei($total) {
        $total=$total?$total:0;
        if(!strstr($total,'.')){
            $total=$total.'.00';
        }
        $total = empty($total)?'0':trim($total.' ');
        $len = strlen($total);
        if ($len>7) { // 万
            $total = (round(($total/10000),2)).'w';
        }
        return $total;
    }
}
