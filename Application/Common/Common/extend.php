<?php
function activity_status($id=0) {
	if (!is_numeric($id) || $id<1) {return null;}
	$arr = ['进行中','未开始','已结束'];
	$time = time();
	$data = M('Document')->field('create_time,deadline')->find($id);
	if ($data) {
		if ($data['deadline']==0) {
			return $data['create_time']>time()?$arr[1]:$arr[0];
		} else {
			return $data['deadline']<time()?$arr[2]:($data['create_time']>time()?$arr[1]:$arr[0]);
		}
	} else {
		return '--';
	}
}
/**
 * 去除两边空格
 * @param array 要操作的数组
 * @return 数组
 * @author 鹿文学
 */
function array_trim($arr) {
	foreach($arr as $k => $v) {
		$arr[$k] = trim($v);
	}
	return $arr;
}
/**
 * 检测礼包所属区服是否关闭
 * @param int 区服编号
 * @return null或数组
 * @author 鹿文学
 */
function check_gift_server($serverid=0) {
	if (is_numeric($serverid) && $serverid>0) {
		return M('Server','tab_')->field('id')->where(array('show_status'=>1,'id'=>$serverid))->find();
	} else {
		return null;
	}
}
/**
 * 获取推荐状态名称
 * @param varchar 状态编号
 * @return n
 * @author cb
 */
function get_recommend_type($type){
    $recommend=explode(',',$type);
    $res=[];
    foreach ($recommend as $key => $value) {
        $res[$key]=get_info_status($value,1);
    }
    return implode(',',$res);
}

/**
 * 获取对应游戏类型的状态信息
 * @ram int $group 状态分组
 * @param int $type  状态文字
 * @return string 状态文字 ，false 未获取到
 * @author
 */
function get_info_status($type=null,$group=0){
    if(!isset($type)){
        return false;
    }
    $arr=array(
        0 =>array(0=>'关闭'   ,1=>'开启'),
        1 =>array(0=>'不推荐' ,1=>'推荐',2=>"热门",3=>'最新'),//游戏设置状态
        2 =>array(0=>'否'     ,1=>'是'),
        3 =>array(0=>'未审核' ,1=>'正常',2=>'锁定'),//推广员状态
        4 =>array(0=>'锁定'   ,1=>'正常'),//用户状态
        5 =>array(0=>'未审核' ,1=>'已审核'   ,2=>'驳回'),//游戏审核状态
        6 =>array(0=>'未修复' ,1=>'已修复'),//纠错状态
        7 =>array(0=>'失败'   ,1=>'成功'),//纠错状态
        8 =>array(0=>'禁用'   ,1=>'启用'),//显示状态
        9 =>array(0=>'下单未付款' ,1=>'充值成功'),//显示状态
        10 =>array(0=>'正常'   ,1=>'拥挤',2=>'爆满'),//区服状态
        12 =>array(0=>'未支付',1=>'成功'),
        13 =>array(1=>'已读',2=>'未读'),
        14 =>array(0=>'通知失败',1=>'通知成功'),
        15 =>array(0=>'未充值',1=>'已充值'),
        16 =>array(0=>'未回复',1=>'已回复'),
        17 =>array(0=>'平台币',1=>'绑定平台币'),
        18 => ['平台币','支付宝','微信','聚宝云'],
        19 => ['审核中','已通过','拒绝'],
        20 => ['','一级','二级'],
        21 => ['平台币','支付宝','微信','微信APP','威富通','','竣付通'],
        22 => ['双平台','安卓','苹果'],
        23 => ['','安卓','苹果'],
        24 => ['','系统分配','自主添加'],
        25 => ['','通过','未审核'],
        26 => ['','开启','关闭'],
        27 => ['','实物','虚拟物品'],
    );
    return $arr[$group][$type];
}

    // lwx 2017-01-07
    function get_systems_name($id,$lan='cn') {
        $list = get_systems_list($lan);
        return $list[$id];
    }

    // lwx 2017-01-07
    function get_systems_list($lan='cn') {
        switch($lan) {
            case 'en':{$list = array(0=>'double',1=>'Android',2=>'IOS');}break;
            default:$list = array(1=>'安卓',2=>'苹果');
        }
        return $list;
    }
    //获取用户所属的推广员ID
    function get_user_pid($id){
        $map['id']=$id;
        $user=M("user","tab_")->where($map)->find();
        return $user['promote_id'];
    }
//游戏玩家人数
function play_num($id){
    $gamedata=M('Game','tab_')->where(array('id'=>$id))->find();
    $count=M('user_play','tab_')->field('count(distinct user_id)')->where(array('game_id'=>$id,'play_time'=>array('gt',$gamedata['set_play_count_time'])))->count();
    if($count==''||$count==null){
        if($gamedata['play_count']){
            $count=$gamedata['play_count'];
        }else{
            $count=0;
        }
    }
    if($gamedata['play_count']){
        $count=$count+$gamedata['play_count'];
    }
    return $count;
}
//游戏收藏人数
function collect_num($id){
    $subQuery=M('user_behavior','tab_')->field('count(*)')->where(array('game_id'=>$id,'status'=>1))->group('user_id')->buildsql();
    $countsql = "select count(*) as num from (".$subQuery.') a';
    $count = M()->query($countsql);
    $count  = $count[0]['num'];
    if($count==''||$count==null){
        $count=0;
    }
    return $count;
}
    // lwx seo替换指定参数为相应的字符
    function seo_replace($str='',$array=array(),$site='media',$meat='title') {
        // if ($site=='channel') {$title = C('CH_SET_TITLE');}
        // else {$title = C('PC_SET_TITLE');}
        switch ($site) {
            case 'channel':
                switch ($meat) {
                    case 'title':
                        $title = C('CH_SET_TITLE');
                        break;
                    case 'keywords':
                        $title = C('CH_SET_META_KEY');
                        break;
                    case 'description':
                        $title = C('CH_SET_META_DESC');
                        break;
                    default:
                        $title = C('CH_SET_TITLE');
                        break;
                }
                break;
            case 'media':
                switch ($meat) {
                    case 'title':
                        $title = C('PC_TITLE');
                        break;
                    case 'description':
                        $title = C('PC_SET_META_DESC');
                        break;
                    default:
                        $title = C('PC_TITLE');
                        break;
                }
                break;
            case 'wap':
                switch ($meat) {
                    case 'title':
                        $title = C('WAP_SET_TITLE');
                        break;
                    case 'keywords':
                        $title = C('WAP_SET_META_KEY');
                        break;
                    case 'description':
                        $title = C('WAP_SET_META_DESC');
                        break;
                    default:
                        $title = C('WAP_SET_TITLE');
                        break;
                }
                
                break;
            default:
                $title = C('PC_SET_TITLE');
                break;
        }
        if(session('union_host')){
            $union_set=json_decode(session('union_host')['union_set'],true);
            $title = $union_set!=''?$union_set['site_name']:$title;
        }
        if (empty($str)) {return $title;}
        $find = array('%webname%','%gamename%','%newsname%','%giftname%','%gametype%','%goodname%');
        $replace = array($title,$array['game_name'],$array['title'],$array['giftbag_name'],$array['game_type_name'],$array['good_name']);
        $str =  str_replace($find,$replace,$str);
        
        return preg_replace('/((-|_)+)?((%[0-9A-Za-z_]*%)|%+)((-|_)+)?/','',$str);
    }

    //读取文件的json数据
    function auto_get_access_token($dirname){
        $appid     = C('wx_login.appid');
        $appsecret = C('wx_login.appsecret');
        $access_token_validity=file_get_contents($dirname);
        if($access_token_validity){
            $access_token_validity=json_decode($access_token_validity,true);
            $is_validity=$access_token_validity['expires_in_validity']-1000>time()?true:false;
        }else{
            $is_validity=false;
        }
        $result['is_validity']=$is_validity;
        $result['access_token']=$access_token_validity['access_token'];
        return $result;
    }
    function auto_get_ticket($dirname){
        $appid     = C('wx_login.appid');
        $appsecret = C('wx_login.appsecret');
        $access_token_validity=file_get_contents($dirname);
        if($access_token_validity){
            $access_token_validity=json_decode($access_token_validity,true);
            $is_validity=$access_token_validity['expires_in_validity']-1000>time()?true:false;
        }else{
            $is_validity=false;
        }
        $result['is_validity']=$is_validity;
        $result['ticket']=$access_token_validity['ticket'];
        return $result;
    }
    function write_text($txt,$name){
        $myfile = fopen($name, "w") or die("Unable to open file!");
        fwrite($myfile, $txt);
        fclose($myfile);
    }
    //判断客户端是否是在微信客户端打开
    function is_weixin(){ 
        if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ) {
            return true;
        }   
        return false;
    }
    function wite_text($txt,$name){
        $myfile = fopen($name, "w") or die("Unable to open file!");
        fwrite($myfile, $txt);
        fclose($myfile);
    }
        /**
     * [获取开放类型名称]
     * @param  string $id [description]
     * @return [type]     [description]
     */
    function get_one_opentype_name($id=''){
        if($id==''){
            return '';
        }
        if($id==0){
             return '公测';
        }
        $data=M('Opentype','tab_')->where(array('id'=>$id))->find();
        if($data==''){
            return false;
        }else{
            return $data['open_name'];
        }
}
/*
    * 获取充值方式（会长代充）
    */
   function get_pay_way1($id=null)
   {
    if(!isset($id)){
        return false;
    }
    switch ($id) {
        case 1:
            return "支付宝";
            break;
        case 2:
            return "微信";
            break;
        case 3:
            return "聚宝云";
            break;
        case 4:
            return "平台币";
            break;
        case 5:
            return "竣付通";
            break;
        default:
            return "支付方式错误";
            break;
    }
   }
   function agent_all_way(){
        $pay_way[1]=array('key'=>1,'value'=>'支付宝');
        $pay_way[2]=array('key'=>2,'value'=>'微信');
        $pay_way[3]=array('key'=>3,'value'=>'聚宝云');
        $pay_way[4]=array('key'=>4,'value'=>'平台币');
        $pay_way[5]=array('key'=>5,'value'=>'竣付通');
        return $pay_way;
   }
/**
 * 子渠道结算状态
 */
function get_son_settlement_stauts($game_id,$promote_id,$str_time,$endtime){
	$result = M('Son_settlement','tab_')
	->where(array('game_id'=>$game_id,'promote_id'=>$promote_id,
			'settlement_start_time'=>$str_time,'settlement_end_time'=>$endtime))
			->find();
	return $result ? 1 : 0;
}
    /**
     * 获取游戏列表
     * @return array，false
     * @author yyh 
     */
    function get_game_list()
     {
        $game = M("game","tab_");
        $map['game_status'] = 1;
        $map['test_status'] = 1;
        $lists = $game->where($map)->select();
        if(empty($lists)){return false;}
        return $lists;
     }

    /**
     * [获取热门游戏列表]
     * @param $rec_status
     * @return bool|mixed
     * @author 幽灵[syt]
     */
    function get_hot_game_list($rec_status) {
        $game = M("game","tab_");
        $map['game_status'] = 1;
        $map['test_status'] = 1;
        $map['recommend_status'] = array('like',"%".$rec_status."%");
        $lists = $game->where($map)->order('id desc')->select();
        if(empty($lists)){return false;}
        return $lists;
    }

    /**
     * [获取推广员申请的游戏]
     * @param int $pid
     * @return mixed|null
     */
    function get_apply_game_list($pid=0) {
        if (is_numeric($pid) && $pid>0) {

            $ids = M('promote','tab_')->field('id')->where(array('parent_id'=>$pid))->select();
            if ($ids) {
                $id = array_column($ids,'id');
            }
            $id[]=$pid.'';

            $apply = M('Apply','tab_');

            return $apply->field('distinct tab_game.id,tab_game.game_name')->join('tab_game on tab_game.id=tab_apply.game_id','right')->where(array('tab_apply.status'=>1,'tab_apply.promote_id'=>array('in',$id)))->select();

        } else {
            return null;
        }
    }
		 
		 
    /**
     * 获取商务专员列表
     *
     */
     function get_busier_list()
     {
     	$map['status'] = 1;
     	$game = M("Busier","tab_");
     	$lists = $game->field('id,busier_account')->where($map)->select();
     	if(empty($lists)){return false;}
     	return $lists;
     }

     /**
      * 获取商务专员账户
      */
     function get_busier_account($id)
     {
     	$map['status'] = 1;
     	$map['id'] = $id;
     	$game = M("Busier","tab_");
     	$lists = $game->field('id,busier_account')->where($map)->find();
     	if(empty($lists)){return false;}
     	return $lists['busier_account'];
     }
    /**
     * 获取第三方游戏列表
     * @return array，false
     * @author yyh
     */
    function get_third_game_list()
    {
        $game = M("game","tab_");
        $map['game_status'] = 1;
        $map['type'] = 0;
        $lists = $game->where($map)->select();
        if(empty($lists)){return false;}
        return $lists;
    }

    /**
     * 获取游戏类型列表
     * @return array，false
     * @author yyh 
     */
    function get_game_type_all() {
        $list = M("Game_type","tab_")->where("status_show=1")->select();
        if (empty($list)) {return '';}
        return $list;
    }

    /**
     * [获取游戏类型名称]
     * @param null $type
     * @return bool
     */
    function get_game_type($type = null){
        if(!isset($type)){
            return false;
        }
        $cl = M("game_type","tab_")->where("status=1 and id=$type")->limit(1)->select();
        return $cl[0]['type_name'];
    }

    /**
     * [获取开放类型列表]
     * @return mixed|string
     */
    function get_opentype_all() {    
        $list = M("Opentype","tab_")->where("status=1")->select();
        if (empty($list)) {return '';}
        return $list;
    }
        /**
    * 生成唯一的APPID
    * @param  $str_key 加密key
    * @return string
    * @author 小纯洁 
    */
    function generate_game_appid($str_key=""){
        $guid = '';  
        $data = $str_key;  
        $data .= $_SERVER ['REQUEST_TIME'];     
        $data .= $_SERVER ['HTTP_USER_AGENT']; 
        $data .= $_SERVER ['SERVER_ADDR'];       
        $data .= $_SERVER ['SERVER_PORT'];      
        $data .= $_SERVER ['REMOTE_ADDR'];     
        $data .= $_SERVER ['REMOTE_PORT'];     
        $hash = strtoupper ( hash ( 'MD4', $guid . md5 ( $data ) ) ); //ABCDEFZHIJKLMNOPQISTWARY
        $guid .= substr ( $hash, 0, 9 ) . substr ( $hash, 17, 8 ) ; 
        return $guid;
    }
    // 获取游戏名称
    function get_game_name($game_id=null,$field='id'){
        $map[$field]=$game_id;
        $data=M('Game','tab_')->where($map)->find();
        if(empty($data)){return ' ';}
        return $data['game_name'];
    }
   /**
    * [获取管理员昵称]
    * @param  integer $id [description]
    * @return [yyh]      [description]
    */
    function get_admin_name($id=0){
        if($id==null){
            return '';
        }
        $data = M("Member")->find($id);
        if(empty($data)){return "";}
        return $data['nickname'];
    }
    /**
     * [获取原包类型]
     * @param  integer $type [description]
     * @return [type]        [description]
     */
    function file_type($file_type='',$type=0){
        if($file_type==''){
            return false;
        }
        if($type){
            $file_type==1?$file_type_name='Android':$file_type_name='IOS';
        }else{
            $file_type==1?$file_type_name='安卓':$file_type_name='苹果';
        }
        return $file_type_name;
    }
    /**
     * [获取游戏appid]
     * @param  [type] $game_name [description]
     * @param  string $field     [description]
     * @param  string $md5       [16位加密]
     * @return [type]            [description]
     * @author yyh <[email address]>
     */
    function get_game_appid($game_name=null,$field='game_name',$md5=false){
        if($game_name==null){
            return false;
        }
        $map[$field]=$game_name;
        $data=M('Game','tab_')->where($map)->find();
        if(empty($data)){return false;}
        if($md5){
            return md5($data['game_appid']);
        }else{
            return $data['game_appid'];
        }
    }
    //生成唯一key
    function create_key(){
        $str = uniqid(mt_rand(),1);
        return sha1($str);
    }
    //获取混服信息
    function get_hun($uid){
        $map['id']=$uid;
        $mix=M('MixPartner','tab_')->where($map)->find();
        if(null==$mix){
        return false;
        }else{
        return $mix;
        }
    }
    /**
     * [字符串转数组  计算个数]
     * @param  [type] $string [description]
     * @return [type]         [description]
     */
    function arr_count($string){
        if($string){
            $arr=explode(',',$string);
            $cou=count($arr);
        }else{
            $cou=0;
        }
        return $cou;
    }

    /**
     * [获取操作平台名称]
     * @param $id
     * @param $table
     * @param string $field
     * @return bool|string
     */
    function get_operation_platform($id,$table,$field='id'){
        if(!$table||!$id){
            return false;
        }
        $model=M($table,'tab_');
        $map[$field]=$id;
        $data = $model->where($map)->find();
        if(empty($data)){
            return false;
        }else{
            if($data['giftbag_version']==0){
                return '双平台';
            }elseif($data['giftbag_version']==1){
                return '安卓';
            }elseif($data['giftbag_version']==2){
                return '苹果';
            }
        }
    }
    /**
     * [获取渠道名称]
     * @param  integer $prmote_id [description]
     * @return [type]             [description]
     */
    function get_promote_name($prmote_id=0){
        if($prmote_id==0){
            return '官方推广员';
        }
        $promote = M("promote","tab_");
        $map['id'] = $prmote_id;
        $data = $promote->where($map)->find();
        if(empty($data)){return '官方推广员';}
        if(empty($data['account'])){return "未知推广";}
        $result = $data['account'];
        return $result;
    }
    /**
     * [根据渠道id 获取上线渠道id]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    function get_parent_id($param,$type='id'){
        $map[$type]=$param;
        $pdata=M('Promote','tab_')->where($map)->find();
        if(empty($pdata)){
            return false;
        }else{
            $p_id=$pdata['parent_id'];
            return $p_id;
        }
    }
/**
 * [pay_way description]
 * @param  [type] $pay_way [description]
 * @return [type]          [description]
 */
    function pay_way($pay_way){
        if($pay_way==-1){
            return '绑定平台币';
        }
        if($pay_way==0){
            return '平台币';
        }
        if($pay_way==1){
            return '支付宝';
        }
        if($pay_way==2){
            return '微信';
        }
        if($pay_way==3){
            return "苹果支付";
        }
    }

    function get_subordinate_promote($param,$type="parent_id"){
        if($param==''){
            return false;
        }
        $map[$type]=$param;
        $data=M('Promote','tab_')
            ->field('account')
            ->where($map)
            ->select();
        return array_column($data,'account');
    }
    /**
     * [二维数组 按照某字段排序]
     * @param  [type] $arrays     [description]
     * @param  [type] $sort_key   [description]
     * @param  [type] $sort_order [description]
     * @param  [type] $sort_type  [description]
     * @return [type]             [description]
     */
    function my_sort($arrays,$sort_key,$sort_order=SORT_ASC,$sort_type=SORT_NUMERIC ){ 
        if(is_array($arrays)){   
            foreach ($arrays as $array){   
                if(is_array($array)){   
                    $key_arrays[] = $array[$sort_key];
               }else{   
                    return false;   
                }   
            }   
        }else{   
            return false;   
        }  
        array_multisort($key_arrays,$sort_order,$sort_type,$arrays);   
        return $arrays;   
    } 
    /**
     * [判断结算时间是否合法]
     * @param  [type] $start      [description]
     * @param  [type] $promote_id [description]
     * @param  [type] $game_id    [description]
     * @return [type]             [description]
     */
    function get_settlement($start,$end,$promote_id,$game_id){
        $start=strtotime($start);
        $end=strtotime($end)+24*60*60-1;
        $map['promote_id']=$promote_id;
        $map['game_id']=$game_id;
        $data=M('settlement','tab_')
            ->where($map)
            ->select();
        foreach ($data as $key => $value) {
            if($start<$value['endtime']){
                if($end>$value['starttime']){
                    return true;//开始时间<结算 不可结算
                }else{
                    return false;
                }
            }
        }
    }
/**
*随机生成字符串
*@param  $len int 字符串长度
*@return string
*@author 小纯洁
*/
function sp_random_string($len = 6) {
    $chars = array(
        "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k",
        "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v",
        "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G",
        "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R",
        "S", "T", "U", "V", "W", "X", "Y", "Z", "0", "1", "2",
        "3", "4", "5", "6", "7", "8", "9"
    );
    $charsLen = count($chars) - 1;
    shuffle($chars);    // 将数组打乱
    $output = "";
    for ($i = 0; $i < $len; $i++) {
        $output .= $chars[mt_rand(0, $charsLen)];
    }
    return $output;
}

/**
 *随机生成字符串
 *@param  $len int 字符串长度
 *@return string
 *@author 小纯洁
 */
function random_string($len = 7) {

    $chars = array(

            "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k",

            "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v",

            "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G",

            "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R",

            "S", "T", "U", "V", "W", "X", "Y", "Z", "0", "1", "2",

            "3", "4", "5", "6", "7", "8", "9"

    );

    $charsLen = count($chars) - 1;

    shuffle($chars);    // 将数组打乱

    $output = "";

    for ($i = 0; $i < $len; $i++) {

        $output .= $chars[mt_rand(0, $charsLen)];

    }

    return $output;

}
/**
*随机生成字符串
*@param  $len int 字符串长度
*@return string
*@author 小纯洁
*/
function sp_random_num($len = 5) {
    $chars = array(
        "0", "1", "2","3", "4", "5", "6", "7", "8", "9"
    );
    $charsLen = count($chars) - 1;
    shuffle($chars);    // 将数组打乱
    $output = "";
    for ($i = 0; $i < $len; $i++) {
        $output .= $chars[mt_rand(0, $charsLen)];
    }
    return $output;
}
/**
 * [获取合作模式]
 * @param  [type] $type [description]
 * @return [type]       [description]
 */
function get_pattern($type){
   if($type==0){
        return "CPS";
    }else{
        return "CPA";
    } 
}
/**
 * [获取日期时间戳]
 * @param  [type] $type [description]
 * @return [type]       [description]
 */
function total($type,$str=true) {
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
    if($str){
        return " between $start and $end ";
    }else{
        return ['between',[$start,$end]];
    }
}
/**
 * [null_to_0 description]
 * @param  [type] $num [description]
 * @return [type]      [description]
 */
function null_to_0($num){
    if($num){
        return sprintf("%.2f",$num);
    }else{
        return sprintf("%.2f",0);
    }
}

/**
 * [获取指定游戏的游戏名称]
 * @param $game_id
 * @return string
 */
function get_game_limit_name($game_id){
    if (empty($game_id)) {
        return '全部游戏';
    } else {
        $game = M("game", "tab_");
        $map['game_status'] = 1;
        $lists = $game->where($map)->find();
        if (empty($lists)) {
            return '';
        }
        return $lists['game_name'];
    }
}
 /**
  * [获取用户实体]
  * @param  integer $id        [description]
  * @param  boolean $isAccount [description]
  * @return [yyh]             [description]
  */
 function get_user_entity($id=0,$isAccount = false){
    if($id =='' ){
        return false;
    }
    $user = M('user',"tab_");
    if($isAccount){
        $map['account'] = $id;
        $data = $user->where($map)->find();
    }
    else{
        $data = $user->find($id);
    }
    if(empty($data)){
        return false;
    }
    return $data;
 }
  /**
  * [获取用户实体]
  * @param  integer $id        [description]
  * @param  boolean $isAccount [description]
  * @return [yyh]             [description]
  */
 function get_user_bytoken($token,$isAccount = false){
    if($token =='' ){
        return false;
    }
    $user = M('user',"tab_");
    if($isAccount){
        $map['token'] = $token;
        $data = $user->where($map)->find();
    }
    else{
        $data = $user->find($id);
    }
    if(empty($data)){
        return false;
    }
    return $data;
 }
 /**
  * [两个运营平台的游戏合并]
  * @param  [type] $data [最新添加的游戏]
  * @param  [type] $map  [description]
  * @return [type]       [游戏信息]
  */
 function game_merge($data,$map){
    $model=M('Game','tab_');
    for ($i=0; $i <count($data); $i++) { 
        if($data[$i]['sdk_version']==2){
            $data[$i]['and_id']='';
            $data[$i]['and_dow_address']='';
            $data[$i]['add_game_address']='';
            $data[$i]['ios_id']=$data[$i]['id'];
            $data[$i]['iosdow']=$data[$i]['dow_num'];
        }else if($data[$i]['sdk_version']==1){
            $data[$i]['ios_id']='';
            $data[$i]['ios_dow_address']='';
            $data[$i]['ios_game_address']='';
            $data[$i]['and_id']=$data[$i]['id'];
            $data[$i]['anddow']=$data[$i]['dow_num'];
        }
        if($data[$i]['relation_game_id']!=$data[$i]['id']){
            //最新添加的游戏id和关联游戏id不一致 即不止一个游戏
            $sibling_id=$data[$i]['relation_game_id'];
            $map['id']=array('eq',$sibling_id);//id不等于关联id(第一个)
            $map['relation_game_id']=$sibling_id;
        }else{
            //最新添加的游戏和关联游戏一致  即只有一个游戏 下面代码可以屏蔽
            $sibling_id=$data[$i]['id'];
            $map['id']=array('neq',$sibling_id);
            $map['relation_game_id']=$data[$i]['relation_game_id'];
        }
        $game_data=$model->where($map)->find();
        if($game_data['sdk_version']==2){
            $data[$i]['ios_id']=$game_data['id'];
            $data[$i]['ios_dow_address']=$game_data['ios_dow_address'];
            $data[$i]['ios_game_address']=$game_data['ios_game_address'];
            $data[$i]['iosdow']=$data[$i]['dow_num'];
        }else if ($game_data['sdk_version']==1){
            $data[$i]['and_id']=$game_data['id'];
            $data[$i]['and_dow_address']=$game_data['and_dow_address'];
            $data[$i]['add_game_address']=$game_data['add_game_address'];
            $data[$i]['anddow']=$data[$i]['dow_num'];
        }
    }
    return $data;
}
/**
 * [获取文本 超过字数显示..]
 * @param  [type] $title [description]
 * @return [type]        [description]
 */
function get_title($title,$len=30){
    if(strlen($title) > $len){
         $title = substr($title, 0,$len).'...';
    }else{
        $title = $title;
    }
    if(empty($title)){return false;}
    return $title;
}
/**
 * [游戏某一礼包所有激活码个数（未领取和已领取）]
 * @param  [type] $gid     [游戏id]
 * @param  [type] $gift_id [礼包 id]
 * @return [type]          [description]
 */
function gift_recorded($gid,$gift_id){
    $wnovice=M('giftbag','tab_')->where(array('game_id'=>$gid,'id'=>$gift_id))->find();
    if($wnovice['is_unicode']){
        $wnovice=$wnovice['unicode_num'];
    }else{
        if($wnovice['novice']!=''){
            $wnovice=count(explode(',',$wnovice['novice']));
        }else{
            $wnovice=0;
        }
    }
    $ynpvice=M('gift_record','tab_')->where(array('game_id'=>$gid,'gift_id'=>$gift_id))->select();
    
    if($ynpvice!=''){
        $ynpvice=count($ynpvice);
    }else{
        $ynpvice=0;
    }
    $return['all']=$wnovice+$ynpvice;
    $return['wei']=(int)$wnovice;
    return $return;
}

/**
 * [返回26个字母的数组集合]
 * @return array
 */
function zimu26(){
    for($i=0;$i<26;$i++){
        $zimu[]['value']=chr($i+65);
    } 
    return $zimu;
}
function get_gift_list($game_id='all'){
    $map['game_status']=1;
    $map['end_time']=array(array('gt',time()),array('eq',0),'or');
    if($game_id!='all'){
        $map['game_id']=$game_id;
    }
    $model = array(
        'm_name'=>'Giftbag',
        'prefix'=>'tab_',
        'field' =>'tab_giftbag.id as gift_id,relation_game_name,game_id,tab_giftbag.game_name,giftbag_name,giftbag_type,tab_game.icon,tab_giftbag.create_time',
        'join'  =>'tab_game on tab_giftbag.game_id = tab_game.id',
        'order' =>'create_time desc',
    );
    $game  = M($model['m_name'],$model['prefix']);
    $data  = $game
    ->field($model['field'])
    ->join($model['join'])
    ->where($map)->order($model['order'])->select();
    return $data;
}
//获取推广员父类id
function get_fu_id($id){
    $map['id']=$id;
    $pro=M("promote","tab_")->where($map)->find();
    if(null==$pro||$pro['parent_id']==0){
        return 0;
    }else{
        return $pro['parent_id'];
    }
}
function get_parent_name($id){
    $map['id']=$id;
    $pro=M("promote","tab_")->where($map)->find();
    if(null!=$pro&&$pro['parent_id']>0){
        $pro_map['id']=$pro['parent_id'];
        $pro_p=M("promote","tab_")->where($pro_map)->find();
        return $pro_p['account'];
    }else if($pro['parent_id']==0){
        return $pro['account'];
    }else{
        return false;
    }
}
/**
 * [用于统计排行  给根据某一字段倒序 获得的结果集插入排序字段 ]
 * @param  [type] $arr [description]
 * @return [type]      [description]
 */
function array_order($arr){
    foreach ($arr as $key => $value) {
        $arr[$key]['rand']=++$i;
    }
    return $arr;
}
/**
*将时间戳装成年月日(不同格式)
*@param  int    $time 要转换的时间戳 
*@param  string $date 类型 
*@return string 
*/
function set_show_time($time = null,$type='time',$tab_type=''){
    $date = "";
    switch ($type) {
        case 'date':
            $date = date('Y-m-d ',$time);
            break;
        case 'time':
            $date = date('Y-m-d H:i',$time);
            break;
        default:
            $date = date('Y-m-d H:i:s',$time);
            break;
    }
    if(empty($time)){//若为空  根据不同情况返回
        if($tab_type==''){
            return "暂无登陆";
        }
        if($tab_type=='forever'){
            return "永久";
        }
        if($tab_type=='other'){
            return "";
        }
    }
    return $date;
}
//判断支付设置
//yyh
function pay_set_status($type){
    $sta=M('tool','tab_')->field('status')->where(array('name'=>$type))->find();
    return $sta['status'];
}
//获取微信支付类型 0官方 1 威富通
function get_wx_type(){
    $map['name']=array('like','%wei%');
    $type=M('Tool','tab_')->where($map)->select();
    foreach ($type as $k => $v) {
    if($v['status']==1){
    $name=$v['name'];
    }
    }
   return $name=="weixin"?1:0;
}
//查询uc用户是否存在该平台
function find_uc_account($name){
    $map['account']=trim($name);
    $user=M('user','tab_')->where($map)->find();
    if(null==$user){
        return false;
    }else{
        return $user;
    }   
}

 /**
*检查链接地址是否有效
*/
function varify_url($url){  
    $check = @fopen($url,"r");  
    if($check){  
     $status = true;  
    }else{  
     $status = false;  
    }    
    return $status;  
} 
//获取当前推广员id
function get_pid()
{
    return $_SESSION['onethink_home']['promote_auth']['pid'];
}
//获取当前联盟推广员id
function get_union_pid()
{
    return $_SESSION['onethink_union']['promote_auth']['pid'];
}

// //计算数组个数用于模板
function arr_count1($string){
    if($string){
        $arr=explode(',',$string);
        $cou=count($arr);
    }else{
        $cou=0;
    }
    return $cou;
}

/**
 *获取推广员子账号
 */
function get_prmoote_chlid_account($id=0){
    $promote = M("promote","tab_");
    $map['status'] = 1;
    $map["parent_id"] = $id;
    $data = $promote->where($map)->select();
    if(empty($data)){return "";}
    return $data;
}

/**
 *获取推广员父类账号  改写
 *@param  $promote_id 推广id
 *@param  $isShow bool
 *@return string
 *@author yyh
 */
function get_parent_promote_($prmote_id=0,$isShwo=true)
{
    $promote = M("promote","tab_");
    $map['id'] = $prmote_id;//本推广员的id
    $data1 = $promote->where($map)->find();//本推广员的记录
    if(empty($data1)){return false;}
    if($data1['parent_id']==0){return false;}
    if($data1['parent_id']){
        $map1['id']=$data1['parent_id'];
    }
    $data = $promote->where($map1)->find();//父类的记录
    $result = "";
    if($isShwo){
        $result = "[{$data['account']}]";
    }
    else{
        $result = $data['account'];
    }
    return $result;
}

//获取当前子渠道
function get_zi_promote_id($id){
    $map['parent_id']=$id;
    $pro=M("promote","tab_")->field('id')->where($map)->select();
    if(null==$pro){
        return 0;
    }else{
        for ($i=0; $i <count($pro); $i++) {
            $sd[]=implode(",", $pro[$i]);
        }
        return  implode(",", $sd);
    }
}

/**
 * 折扣信息
 * @param $data
 * @return mixed
 */
function discount_data($data){
    if($data['recharge_status'] != 1){
        $game = M('game','tab_')->find($data['game_id']);
        $game_discount = $game['discount'];
        $data['promote_discount'] = $game_discount;
    }
    if($data['promote_status'] != 1 || empty($data['first_discount'])){
        $data['first_discount'] = 10;
    }
    if($data['cont_status'] != 1 || empty($data['continue_discount'])){
        $data['continue_discount'] = 10;
    }
    return $data;
}

/**
 *设置状态文本
 */
function get_status_text($index=1,$mark=1){
    $data_text = array(
        0  => array( 0 => '失败' ,1 => '成功'),
        1  => array( 0 => '锁定' ,1 => '正常'),
        2  => array( 0 => '未申' ,1 => '已审' , 2 => '拉黑'),
        3  =>array(0=>'不参与',1=>'已参与'),
        4 => ['系统','上级推广员'],
    );
    return $data_text[$index][$mark];
}

/**
 * 商务专员注册个数统计
 */
/* function get_busier_num($string){
	$data = explode(',', $string);
	return count($data);
} */
function get_busier_num($id){
    $map['parent_id'] = 0;
    $map['busier_id'] = $id;
    return M('Promote','tab_')->field('id,account')->where($map)->count();
}


/**
 * 商务专员下级用户
 */
function get_busier_user($id){
    $map['busier_id'] = $id;
    $map['parent_id'] = 0;
    $promote = M('Promote','tab_')->field('id,account')->where($map)->select();
    return $promote;
}

/**
 * 渠道结算根据订单求某一字段和
 */
function get_settlemt_sum($order,$string){
	$sum = M('Settlement','tab_')->where(array('settlement_number'=>$order))->sum($string);
	return $sum;
}
/**
 * 子渠道订单根据字段求和
 */
function get_son_settlemt_sum($order,$string){
	$sum = M('Son_settlement','tab_')->where(array('settlement_number'=>$order))->sum($string);
	return $sum;
}
/**
 * 获取推广员帐号
 */
function get_promote_account($promote_id){
    if($promote_id == 0){
        return '官方推广员';
    }
    $data = M('promote','tab_')->find($promote_id);
    $account = empty($data['account']) ? '系统' : $data['account'];
    return $account;
}

/**
 * 获取渠道等级
 * @param $promote_id
 * @return mixed
 */
function get_promote_level($promote_id){
    $model = M('promote','tab_');
    $map['id'] = $promote_id;
    $data = $model->where($map)->find();
    if(empty($data)){
        return '';
    }
    if($data['parent_id'] == 0) {
        return '1';
    }else{
        return 2;
    }
}
/**
 * [获取游戏原包数据]
 * @param  [type] $and_id [安卓版本id]
 * @param  [type] $ios_id [苹果版本id]
 * @return [type]         [description]
 * @author [yyh] <[email address]>
 */
function get_game_source($and_id,$ios_id){
    $model = M('Game_source','tab_');
    if($and_id&&$ios_id){
        $map['game_id']=array('in',$and_id.','.$ios_id);
    }else if($and_id||$ios_id){
        if($and_id){
            $map['game_id']=$and_id;
        }else{
            $map['game_id']=$ios_id;
        }
    }else{
        return false;
    }
    $data=$model->where($map)->select();
    foreach ($data as $key => $value) {
        if($value['game_id']==$and_id){
            $dataa['and_id']=$data[$key];
        }
        if($value['game_id']==$ios_id){
            $dataa['ios_id']=$data[$key];
        }
    }
    return $dataa;
}

/**
 * [获取扩展状态]
 * @param  [type] $name [description]
 * @return [type]       [description]
 */
function get_tool_status($name){
    $map['name']=$name;
    $tool=M("tool","tab_")->where($map)->find();
    return $tool['status'];
}
//二级推广员id
function get_child_ids($id){
        $map1['parent_id']=$id;
        $map1['id']=$id;
        $map1['_logic']='OR';
        $arr1=M('promote','tab_')->where($map1)->field('id')->select();
        if($arr1){
            return $arr1;
        }else{
            return false;
        }
        
    }
//获取图片连接
function icon_url($value){
    if (get_tool_status("oss_storage") == 1){
        $url=get_cover($value, 'path');
    }else{
        if(get_cover($value, 'path')){
            $url='http://' . $_SERVER['HTTP_HOST'] . get_cover($value, 'path');
        }else{
            $url = "";
        }
    }
    return $url;
}

//获取广告数据
function get_adv_data($pos_name){
    $adv = M("Adv","tab_");
    $map['tab_adv.status'] = 1;
    $map['tab_adv.start_time']=array(array('lt',time()),array('eq',0), 'or') ;
    $map['tab_adv.end_time']=array(array('gt',time()),array('eq',0), 'or') ;
    $data = $adv
        ->field('tab_adv.*,p.width,p.height')
        ->where($map)
        ->join("right join  tab_adv_pos as p on p.name = '{$pos_name}' and p.id = tab_adv.pos_id")
        ->order('sort desc')
        ->select();
    return $data;
}

//根据游戏ID获取游戏的全部数据
function game_entity_data($game_id = 0){
    $game = M('game','tab_');
    $map['id'] = $game_id;
    $entity = $game->where($map)->find();
    if(empty($entity)){
        return false;
    }
    return $entity;
}

function get_refund_pay_order_number($order)
{
    $map['order_number']=$order;
    $find=M('refund_record','tab_')->where($map)->find();
    return $find['pay_order_number'];
}


/**
 * 判断手机访问型号
 * @return string
 */
function get_device_type()
{
    //全部变成小写字母
    $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
    $type = 'other';
    //分别进行判断
    if (strpos($agent, 'iphone') || strpos($agent, 'ipad')) {
        $type = 'ios';
    }

    if (strpos($agent, 'android')) {
        $type = 'android';
    }
    return $type;
}
//判断是否是手机端请求
function is_mobile_request()   {    
    $_SERVER['ALL_HTTP'] = isset($_SERVER['ALL_HTTP']) ? $_SERVER['ALL_HTTP'] : '';    
    $mobile_browser = '0';    
    if(preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|iphone|ipad|ipod|android|xoom)/i', strtolower($_SERVER['HTTP_USER_AGENT'])))     $mobile_browser++;    
    if((isset($_SERVER['HTTP_ACCEPT'])) and (strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') !== false))     
        $mobile_browser++;    
    if(isset($_SERVER['HTTP_X_WAP_PROFILE']))     
        $mobile_browser++;    
    if(isset($_SERVER['HTTP_PROFILE']))     
        $mobile_browser++;    
        $mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'],0,4));    
        $mobile_agents = 
        array(       
            'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',       
            'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',       
            'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',       
            'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',       
            'newt','noki','oper','palm','pana','pant','phil','play','port','prox',       
            'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',       
            'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',       
            'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',       
            'wapr','webc','winw','winw','xda','xda-'     
        );    
    if(in_array($mobile_ua, $mobile_agents))     
        $mobile_browser++;    
    if(strpos(strtolower($_SERVER['ALL_HTTP']), 'operamini') !== false)     
        $mobile_browser++;    
    // Pre-final check to reset everything if the user is on Windows    
    if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows') !== false)     
        $mobile_browser=0;    
    // But WP7 is also Windows, with a slightly different characteristic    
    if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows phone') !== false)     
        $mobile_browser++;    if($mobile_browser>0)     
        return true;    
    else   
        return false; 
}
//获取合作者key
function get_key($id,$to=1){    
    $map_['id']=$id;
    $mix_partner=M("MixPartner",'tab_')->where($map_)->find();
    if($to==1){
        return $mix_partner['login_key'];
    }else{
        return $mix_partner['pay_key'];
    }
}
/**
 * 简单对称加密算法之加密
 * @param String $string 需要加密的字串
 * @param String $skey 加密EKY
 * @author yyh
 * @return String
 */
function simple_encode($string = '', $skey = 'mengchuang') {
    $strArr = str_split(base64_encode($string));
    $strCount = count($strArr);
    foreach (str_split($skey) as $key => $value)
        $key < $strCount && $strArr[$key].=$value;
    return str_replace(array('=', '+', '/'), array('O0O0O', 'o000o', 'oo00o'), join('', $strArr));
}
/**
 * 简单对称加密算法之解密
 * @param String $string 需要解密的字串
 * @param String $skey 解密KEY
 * @author yyh
 * @return String
 */
function simple_decode($string = '', $skey = 'mengchuang') {
    $strArr = str_split(str_replace(array('O0O0O', 'o000o', 'oo00o'), array('=', '+', '/'), $string), 2);
    $strCount = count($strArr);
    foreach (str_split($skey) as $key => $value)
        $key <= $strCount  && isset($strArr[$key]) && $strArr[$key][1] === $value && $strArr[$key] = $strArr[$key][0];
    return base64_decode(join('', $strArr));
}
function format_date($time){
    $t=time()-$time;
    $f=array(
        '31536000'=>'年',
        '2592000'=>'个月',
        '604800'=>'星期',
        '86400'=>'天',
        '3600'=>'小时',
        '60'=>'分钟',
        '1'=>'秒'
    );
    foreach ($f as $k=>$v)    {
        if (0 !=$c=floor($t/(int)$k)) {
            return $c.$v.'前';
        }
    }
}

function get_links($mark=0){
    $map['mark'] = $mark;
    $links = M("Links","tab_")->where("mark=0")->select();//友情链接
    return $links;
}

//签名字符串方法
function sortData($data)
{
    ksort($data);
    foreach ($data as $k => $v) {
        $tmp[] = $k . '=' . urlencode($v);
    }
    $str = implode('&', $tmp) . $secret;
    return $str;
}
//签名方法
function signsortData($data, $secret)
{
    ksort($data);
    foreach ($data as $k => $v) {
        $tmp[] = $k . '=' . urlencode($v);
    }
    $str = implode('&', $tmp) . $secret;
    return md5($str);
}
function is_https() {
    if ( !empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off') {
        return true;
    } elseif ( isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https' ) {
        return true;
    } elseif ( !empty($_SERVER['HTTP_FRONT_END_HTTPS']) && strtolower($_SERVER['HTTP_FRONT_END_HTTPS']) !== 'off') {
        return true;
    }
    return false;
}


//对年龄的审核
function age_verify($cardno,$name){
    $date = age($cardno,$name);

    if ($date['resp']['code']==0 && $date>0){
        $age = floor((time()-strtotime($date['data']['birthday']))/(60*60*24*365));
        if ($age > 17){
            return 1;
        }else{
            return 2;
        }
    }elseif($date['resp']['code']!=0 && $date>0){
        return 0;
    }else{
        return $date;
    }
}
//根据配置向接口发送身份证号和姓名进行验证
function age($cardno,$name){
    $host = "http://idcard.market.alicloudapi.com";
    $path = "/lianzhuo/idcard";
    $method = "GET";
    $appcode = C('age.appcode');
    $headers = array();
    array_push($headers, "Authorization:APPCODE " . $appcode);
    $querys = "cardno=".$cardno."&name=".$name;
    $bodys = "";
    $url = $host . $path . "?" . $querys;
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_FAILONERROR, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HEADER, true);
    if (1 == strpos("$".$host, "https://"))
    {
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    }
    $output=curl_exec($curl);
    if (empty($output)){
        return -1;//用完
    }
    if(curl_getinfo($curl,CURLINFO_HTTP_CODE)=='200'){
        $headersize=curl_getinfo($curl,CURLINFO_HEADER_SIZE);
        $header=substr($output,0,$headersize);
        $body=substr($output,$headersize);
        curl_close($curl);
        return json_decode($body,true);
    }else{
        return -2;//失败
    }
}
function ageVerify($cardno,$name){
    // $date = age($cardno,$name);
    $appCode = C('age.appcode');
    //身份证号码
    $params['cardNo']=$cardno;
    //身份证姓名
    $params['realName']=$name;
    $date = AGEAPISTORE($params,$appCode);
    if(!empty($date)){
        if($date['result']['isok']!=1){
        return 0;//认证失败
        }else{
            $age = floor((time()-strtotime($date['result']['details']['birth']))/(60*60*24*365));
            if ($age > 17){
                return 1;//已成年
            }else{
                return 2;//未成年
            }
        }
    }else{
       $date = age($cardno,$name);
        if ($date['resp']['code']==0 && $date>0){
            $age = floor((time()-strtotime($date['data']['birthday']))/(60*60*24*365));
            if ($age > 17){
                return 1;
            }else{
                return 2;
            }
        }elseif($date['resp']['code']!=0 && $date>0){
            return 0;
        }else{
            return $date;
        }
    }
   
}
function AGEAPISTORE($params = null, $appCode,$url="http://1.api.apistore.cn/idcard")
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url . '?' . http_build_query($params));
    curl_setopt($curl, CURLOPT_HTTPHEADER, array (
        'Authorization:APPCODE '.$appCode
    ));
    //如果是https协议
    if (stripos($url, "https://") !== FALSE) {
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        //CURL_SSLVERSION_TLSv1
        curl_setopt($curl, CURLOPT_SSLVERSION, 1);
    }
    //超时时间
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 60);
    curl_setopt($curl, CURLOPT_TIMEOUT, 60);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    //返回内容
    $callbcak = curl_exec($curl);
    //关闭,释放资源
    curl_close($curl);
    file_put_contents(dirname(__FILE__).'/aaaa.txt', $callbcak);
    //返回内容JSON_DECODE
    return json_decode($callbcak, true);
}

//用php从身份证中提取生日,包括15位和18位身份证 
function getIDCardInfo($IDCard){ 
    $result['error']=0;//0：未知错误，1：身份证格式错误，2：无错误 
    $result['flag']='';//0标示成年，1标示未成年 
    $result['tdate']='';//生日，格式如：2012-11-15 
    if(!eregi("^[1-9]([0-9a-zA-Z]{17}|[0-9a-zA-Z]{14})$",$IDCard)){ 
        $result['error']=1; 
        return $result; 
    }else{ 
        if(strlen($IDCard)==18){ 
            $tyear=intval(substr($IDCard,6,4)); 
            $tmonth=intval(substr($IDCard,10,2)); 
            $tday=intval(substr($IDCard,12,2)); 
            if($tyear>date("Y")||$tyear<(date("Y")-100)){ 
                $flag=0; 
            }elseif($tmonth<0||$tmonth>12){ 
                $flag=0; 
            }elseif($tday<0||$tday>31){ 
                $flag=0; 
            }else{ 
                $tdate=$tyear."-".$tmonth."-".$tday." 00:00:00"; 
                if((time()-mktime(0,0,0,$tmonth,$tday,$tyear))>18*365*24*60*60){ 
                    $flag=0; 
                }else{ 
                    $flag=1; 
                } 
            } 
        }elseif(strlen($IDCard)==15){ 
            $tyear=intval("19".substr($IDCard,6,2)); 
            $tmonth=intval(substr($IDCard,8,2)); 
            $tday=intval(substr($IDCard,10,2)); 
            if($tyear>date("Y")||$tyear<(date("Y")-100)){ 
                $flag=0; 
            }elseif($tmonth<0||$tmonth>12){ 
                $flag=0; 
            }elseif($tday<0||$tday>31){ 
                $flag=0; 
            }else{ 
                $tdate=$tyear."-".$tmonth."-".$tday." 00:00:00"; 
                if((time()-mktime(0,0,0,$tmonth,$tday,$tyear))>18*365*24*60*60){ 
                    $flag=0; 
                }else{ 
                    $flag=1; 
                } 
            } 
        } 
    } 
    $result['error']=2;//0：未知错误，1：身份证格式错误，2：无错误 
    $result['isAdult']=$flag;//0标示成年，1标示未成年 
    $result['birthday']=$tdate;//生日日期 
    return $result; 
}

//获取QQUID
function get_union_id($access_token){
    $url = "https://graph.qq.com/oauth2.0/me?access_token=".$access_token."&unionid=1";
    $content = file_get_contents($url);
    $packname1 = '/\"unionid\"\:\"(.*?)\"/';
    preg_match($packname1,$content,$packname2);
    $packname = $packname2[1];
    return $packname;
}

//获取游戏图标
function getgameicon($game_id){
    if(!$game_id) return false;
    $icon_id = M('Game','tab_')->field('icon')->where(array('id'=>$game_id))->find();
    if(empty($icon_id)) return false;
    $icon_url = icon_url($icon_id['icon']);
    return $icon_url;
}

//获取游戏http或https头
function get_http_url(){
    $type = C('IS_HTTPS');
    if($type==1){
        return 'https://';
    }else{
        return 'http://';
    }
}
/**
 * @param    {int}  pid     一级推广员
 * @param    {int}   bid    商务专员
 * @author   yyh
*/
function set_busier_id($pid,$bid){
    $zi_promote_arr = get_zi_promote_id($pid);
    if(!$zi_promote_arr){
        return 1;
    }else{
        $data['busier_id'] = $bid;
        $map['id'] = array('in',$zi_promote_arr);
        $res = M('Promote','tab_')->where($map)->save($data);
        return $res;
    }
}

//删除商务专员
function del_busier_id($pid){
    $zi_promote_arr = get_zi_promote_id($pid);
    if(!$zi_promote_arr){
        return 1;
    }else{
        $data['busier_id'] = 0;
        $map['id'] = array('in',$zi_promote_arr);
        $res = M('Promote','tab_')->where($map)->save($data);
        return $res;
    }
}

/**
 * 是否开启缓存
 * @return boolean [description]
 * @author chenbin <[email address]>
 */
function is_cache(){
    if(C('CACHE_TYPE')==1||C('CACHE_TYPE')==2){
        return true;
    }else{
        return false;
    }
}


/**
 * [返回需要的礼包列表页]
 * @param bool $all
 * @return array
 * @author 幽灵[syt]
 */
function get_kefu_lists($all = false,$field=''){
    if ($all){
        $typelist = array('often'=>'客服问题','jifen'=>'积分规则','gift'=>'礼包常见问题');
    }else{
        $typelist = array('changjian'=>'常见问题','mima'=>'密码问题','zhanghu'=>'账户问题','chongzhi'=>'充值问题','gift'=>'礼包中心','jifen'=>'积分商城');
    }
    if (!empty($field)){
        return $typelist[$field];
    }else{
        return $typelist;
    }
}

/**
 * [判断每日任务是否完成]
 * @param $user_id
 * @param $name
 * @author 幽灵[syt]
 */
function daily_task($user_id,$key){

    if (empty($user_id)){
        return false;
    }

    $map['user_id'] = $user_id;
    $map['key'] = $key;
    $map['tab_point_record.create_time'] = array('gt',strtotime(date('Y-m-d')));

    $data = M('PointType','tab_')
        ->where($map)
        ->field('tab_point_record.id')
        ->join('tab_point_record ON tab_point_record.type_id = tab_point_type.id')
        ->find();

    if ($data){
        return true;
    }else{
        return false;
    }
}