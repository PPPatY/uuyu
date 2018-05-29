<?php
/**
 * 后台公共文件扩展
 * 主要定义后台公共函数库
 */

 //根据游戏id获取游戏唯一标示
function get_marking($id)
{
    $map['id']=$id;
    $game=M("game","tab_")->where($map)->find();
    return $game['marking'];
}
function get_auth_group_name($uid){
    $model = D("auth_group_access");
    $res = $model->join("sys_auth_group on sys_auth_group.id = sys_auth_group_access.group_id")
    ->field("title")
    ->where(array('uid'=>$uid))
    ->find();
    return $res["title"];
}
//根据发送消息的ID获取通知名字
function get_push_name($id)
{
    $map['id']=$id;
    $list=M("push","tab_")->where($map)->find();
    if(empty($list)){return false;}
    return $list['push_name'];
}
//获取推送通知应用
function get_push_list()
{
    $list=M("push","tab_")->select();
    if(empty($list)){return false;}
    return $list;
}

function get_promote_list($select='') {
    $list = M("Promote","tab_")->where("status=1")->select();
    if (empty($list)){return '';}
    return $list;
}
/**
 * [获取所有一级推广员]
 * @return [type] [description]
 */
function get_all_toppromote(){
    $map['status']=1;
    $map['parent_id']=0;
    $list = M("Promote","tab_")->where($map)->select();
    if (empty($list)){return '';}
    return $list;
}


//获取支付方式的中文名称
function get_pay_way($id=null)
{
     if(!isset($id)){
         return false;
     }
     switch ($id) {
        case -1:
             return "绑定平台币";
            break;
        case 0:
             return "平台币";
            break;
         case 1:
            return "支付宝";
             break;
        case 2:
             return "微信";
            break;
        case 3:
             return "微信APP";
            break;
       case 4:
               return "威富通";
             break;
       case 6:
               return "竣付通";
             break;    
        case 7:
               return "金猪支付";
             break;    
         default:
              return "支付方式错误";
            break;
       }

   }

   //通过文章栏目ID 返回链接
   function get_highlight_subnav($cate_id=0,$url='Article/index',$param='cate_id'){
        $map['sys_category.id'] = $cate_id;
        $cate_id = D('category')
        ->field("tab.id,tab.title,tab.pid,tab.sort")
        ->join("sys_category AS tab ON sys_category.pid = tab.pid")
        ->where($map)
        ->order('tab.sort asc')
        ->getField('tab.id',1);
        $url = U($url,array('cate_id'=>$cate_id));
        return $url;
   }

function all_pay_way($type=false)
{
    if($type=='all'){
        $pay_way[-1]=array('key'=>-1,'value'=>'绑定平台币');
        $pay_way[0]=array('key'=>0,'value'=>'平台币');
    }elseif($type){
        $pay_way[0]=array('key'=>0,'value'=>'平台币');
    }
    $pay_way[1]=array('key'=>1,'value'=>'支付宝');
    $pay_way[2]=array('key'=>2,'value'=>'微信');
    $pay_way[6]=array('key'=>6,'value'=>'竣付通');
    $pay_way[7]=array('key'=>7,'value'=>'金猪支付');
    return $pay_way;
}
//获取支付方式
//获取支付方式
function get_register_way($id=null)
{
    if(!isset($id)){
        return false;
    }
    switch ($id) {
        case 0:
          return "游客注册";
            break;
        case 1:
          return "账号注册";
            break;
        case 2:
          return "手机注册";
        case 3:
          return "微信注册";
        case 4:
          return "QQ注册";
        case 5:
          return "百度注册";
        case 6:
          return "微博注册";
            break;
    }
}
//判断用户是否存在
function get_user_one_list($args){
    if(empty($args))return false;
    $user = D('User');
    $map['account']=$args;
    $data = $user->where($map)->find();
    return $data;
}
//所有支付方式
function all_register_way($type=false)
{   
    if($type!==false){
        if($type==1){
            $pay_way[3]=array('key'=>3,'value'=>'微信注册');
            $pay_way[4]=array('key'=>4,'value'=>'QQ注册');
            $pay_way[5]=array('key'=>5,'value'=>'百度注册');
            $pay_way[6]=array('key'=>6,'value'=>'微博注册');
        }else{
            $pay_way[0]=array('key'=>0,'value'=>'游客注册');
            $pay_way[1]=array('key'=>1,'value'=>'账号注册');
            $pay_way[2]=array('key'=>2,'value'=>'手机注册');
        }
    }else{
        $pay_way[0]=array('key'=>0,'value'=>'游客注册');
        $pay_way[1]=array('key'=>1,'value'=>'账号注册');
        $pay_way[2]=array('key'=>2,'value'=>'手机注册');
        $pay_way[3]=array('key'=>3,'value'=>'微信注册');
        $pay_way[4]=array('key'=>4,'value'=>'QQ注册');
        $pay_way[5]=array('key'=>5,'value'=>'百度注册');
        $pay_way[6]=array('key'=>6,'value'=>'微博注册');
    }
    return $pay_way;
}
/**
 * 根据用户账号 获取用户id
 * @param  [type] $account [用户账号]
 * @return [type] id       [用户id]
 * @author [yyh] 
 */
function get_user_id($account){
    $map['account']=$account;
    $user=D("User")->where($map)->find();
    return $user['id'];
}
/**
 * 根据用户账号 获取用户昵称
 * @param  [type] $account [用户账号]
 * @return [type] user_nickname       [用户]
 * @author [yyh] 
 */
function get_user_nickname($account){
    $map['account']=$account;
    $user=M("user_play","tab_")->where($map)->find();
    return $user['user_nickname'];
}
//判断用户是否玩此游戏 author：yyh
function get_play_user($account,$gid){
    if(empty($account))return false;
    $user = D('User');
    $map['account']=$account;
    $map['game_id']=$gid;
    $data = $user
    ->join('tab_user_play on tab_user.account=tab_user_play.user_account')
    ->where($map)
    ->find();
    return $data;
}
//生成订单号
function build_order_no(){
    return date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
}
/**
 * [get_game_id description]根据游戏名称 获取游戏id
 * @param  [type] $name [游戏名称]
 * @return [type]       [id]
 * @author [yyh] <[email address]>
 */
function get_game_id($name){
    $game=M('game','tab_');
    $map['game_name']=$name;
    $data=$game->where($map)->find();
    if($data['id']==null){
        return false;
    }
    return $data['id'];
}

/**
 * 获取混服合作者
 */
function get_mix_partner_lists() {
    $list = M("MixPartner","tab_")->where("status=1")->select();
    if (empty($list)){return '';}
    return $list;
}

/**
 * 获取混服合作者
 */
function get_mix_partner_name($id=0) {
    $map['id'] = $id;
    $map['status'] = 1;
    $list = M("MixPartner","tab_")->where($map)->find();
    if (empty($list)){return '';}
    return $list['account'];
}
function mix_pay_status($id)
{
    if($id==1){
        return "已支付";
    }else{
        return "未支付";
    }
}
//混服申请表状态
function mix_status($status){
    switch ($status) {
        case -1:
            return "未审核";
            break;
        case 1:
            return "开启";
            break;
        case 0:
            return "关闭";
            break;
        default:
            return "未审核";
            break;
    }
}


function get_transfe($id){
    if($id==1){
        return "联运方";
    }else{
        return "混服方";
    }
}
/**
 * [ratio_stytl 数值转百分比
 * @param  integer $num [description]
 * @return [yyh]       [description]
 */
function ratio_stytl($num = 0){
    return $num."%";
}
/**
 * [get_user_account 根据用户id 获取用户账号]
 * @param  [type] $uid [用户id]
 * @return [type] account     [用户账号]
 * @author [yyh] <[email address]>
 */
function get_user_account($uid=null){
    if(empty($uid)){return false;}
    $user = D('User');
    $map['id'] = $uid;
    $data = $user->where($map)->find();
    if(empty($data['account'])){return false;}
    return $data['account'];
}
/**
 * [checked_game description]
 * @param  [type] $id         [description]
 * @param  [type] $sibling_id [description]
 * @return [type]             [description]
 */
function checked_game($id,$sibling_id){
    if($sibling_id){
        $map['id']=array('neq',$id);
        $map['sibling_id']=$sibling_id;
        $game=M('Game','tab_')->where($map)->find();
        if(empty($game)){
            return '';
        }else{
            return $game;
        }
    }else{
        return false;
    }
}

/**
 * [获取游戏原包文件版本]
 * @param  [type] $game_id [description]
 * @param  string $type    [description]
 * @return [type]          [description]
 */
function get_game_version($game_id,$type=''){
    $model=M('game_source force index (`game_id`)','tab_');
    if($game_id==''){
        return '';
    }
    $map['game_id']=$game_id;
    $map['file_type']=$type;
    $data=$model
        ->where($map)
        ->select();
    return $data;
}
/**
 * [获取游戏版本]
 * @param  [type] $id [description]
 * @return [type]     [description]
 */
function game_version($id){
    $game=M('game','tab_');
    $map['id']=$id;
    $data=$game->where($map)->find();
    if($data['id']==null){
        return false;
    }
    return $data['version'];
}
// 获取IOS游戏名称
function get_ios_game_name($game_id=null,$field='id'){
    $map[$field]=$game_id;
    $map['game_version']=0;
    $data=M('Game','tab_')->where($map)->find();
    if(empty($data)){return false;}
    $game_name=explode("(", $data['game_name']);
    return $game_name[0];
}
function get_game_icon_id($id)
{
    $map['id']=$id;
    $data=M("game","tab_")->where($map)->find();
    return $data['icon'];
}
/**
 * [获取区服名称]
 * @param  [type] $id [description]
 * @return [type]     [description]
 */
function get_server_name($id){
    if($id==''){
        return false;
    }
    $map['id']=$id;
    $area=M("Server","tab_")->where($map)->find();
    return $area['server_name'];
}
/**
 * [获取游戏区服名称]
 * @param  [type] $area_id [description]
 * @return [type]          [description]
 */
function get_area_name($area_id= null){
    if(empty($area_id)){return false;}
    $area_model = D('Server');
    $map['id'] = $area_id;
    $name = $area_model->where($map)->find();
    if(empty($name['server_name'])){return false;}
    return $name['server_name'];
}
/**
 * [获取管理员列表]
 * @return [type] [description]
 */
function get_admin_list()
{
    $list= M("Member")->where("status=1")->select();
    if(empty($list)){return false;}
    return $list;
}
/**
 * [渠道等级]
 * @param  [type] $pid [description]
 * @return [type]      [description]
 */
function get_qu_promote($pid){
    if($pid==0){
        return "一级推广员";
    }else{
        return "二级推广员";
    }
}
/**
 * [上线渠道]
 * @param  [type] $id  [description]
 * @param  [type] $pid [description]
 * @return [type]      [description]
 */
function get_top_promote($id,$pid){
    if($pid==0){
        $pro=M("promote","tab_")->where(array('id'=>$id))->find();
    }else{
        $map['id']=$pid;
        $pro=M("promote","tab_")->where($map)->find();
    }   
        if($pro==''){
            return false;
        }
        return $pro['account'];
}
/**
 * 获取管理员昵称 二级跟随一级  
 * @param  [type] $parent_id [description]
 * @param  [type] $admin_id  [description]
 * @return [type]            [description]
 */
function get_admin_nickname($parent_id = 0,$admin_id=-1){
    if($parent_id){
        $map['id']=$parent_id;
        $pad=M('Promote',"tab_")->where($map)->find();
        if(empty($pad['admin_id'])){return false;}
        $user = D('member');
        $map1['uid'] = $pad['admin_id'];
        $data = $user->where($map1)->find();
        if(empty($data['nickname'])){return false;}
        return $data['nickname'];
    }elseif($parent_id==0&&$admin_id!=0){
        $user1 = D('member');
        $map2['uid'] = $admin_id;
        $data = $user1->where($map2)->find();
        if(empty($data['nickname'])){return false;}
        return $data['nickname'];
    }else{
        if($admin_id==0){
           return get_admin_nickname($parent_id,C('USER_ADMINISTRATOR'));
        }else{
            return false;
        }
    }
}
/**
 * [根据推广员获取所属专员]
 * @param  [type] $id [description]
 * @return [type]     [description]
 */
function get_belong_admin($id)
{
    $map['id']=$id;
    $pro=M("promote","tab_")->where($map)->find();
    if(!empty($pro)){
     return get_admin_nickname($pro['parent_id'],$pro['admin_id']);
    }else{
        return false;
    }
}
/**
 * [根据推广员获取所属专员]
 * @param  [type] $id [description]
 * @return [type]     [description]
 */
function get_admin_promotes($param,$type='admin_id')
{
    $map[$type]=$param;
    $pro=M("promote","tab_")->where($map)->select();
    return $pro;
}
/**
 * [根据推广员id获取上级推广员姓名]
 * @param  [type] $id [description]
 * @return [type]     [description]
 */
function get_parent_promoteto($id){
    if($id==''){
        return '';
    }
    $list=D("promote");
    $map['id']=$id;    
    $pid=$list->where($map)->find();
    if($pid['parent_id']!=0){
        $mapp['id']=$pid['parent_id'];
        $pname=$list->where($mapp)->find();
        if($pname){
            return "[".$pname['account']."]";    
        }
        else{
            return "";
        }
    }else{
        return "";   
    }
}
/**
 * 根据推广员ID取得商务专员数据
 */
function get_busier_nickname($id){
	$pro_data = M('Promote','tab_')->where(array('id'=>$id))->find();
	$data = get_busier_account($pro_data['busier_id']);
	if($data){
		return $data;
	}else{
        return '--';
    }
}
 //获取注册来源
function get_registertype($way){
    if(!isset($way)){
        return false;
    }
    $arr=array(
        0=>'游客注册',
        1=>'账号注册',
        2=>'手机注册',
        3=>'微信注册',
        4=>'QQ注册',
        5=>'百度注册',
        6=>'微博注册',
    );
    return $arr[$way];
}
//获取注册方式
function get_registerway($type){
    if(!isset($type)){
        return false;
    }
    $arr=array(
        0=>"游客",
        1=>"账号",
        2 =>"手机",
        3=>"微信",
        4=>"QQ",
        5=>"百度",
        6=>"微博",

    );
    return $arr[$type];
}
//获取推广员id
function get_promote_id($name){
    $promote=M('Promote','tab_');
    $map['account']=$name;
    $data=$promote->where($map)->find();
    if(empty($data)){
        return '';
    }else{
        return $data['id'];
    }
}
//获取管理员id
function get_admin_id($name){
    $promote=M('Member','sys_');
    $map['nickname']=$name;
    $data=$promote->where($map)->find();
    if(empty($data)){
        return '';
    }else{
        return $data['uid'];
    }
}
//获取所有用户列表
function get_user_list(){
    $user = M('User','tab_');
    $list = $user->field('id,account')->select();
    return $list;
}
/**
 * [array_group_by 二维数组根据里面元素数组的字段 分组]
 * @param  [type] $arr [description]
 * @param  [type] $key [description]
 * @return [type]      [description]
 */
function array_group_by($arr, $key){
        $grouped = [];
        foreach ($arr as $value) {
            $grouped[$value[$key]][] = $value;
        }
        if (func_num_args() > 2) {
            $args = func_get_args();
            foreach ($grouped as $key => $value) {
                $parms = array_merge([$value], array_slice($args, 2, func_num_args()));
                $grouped[$key] = call_user_func_array('array_group_by', $parms);
            }
        }
        return $grouped;
}
/**
 * [前几个月]
 * @param  integer $m [前几个月]
 * @return [type]     [description]
 */
function before_mounth($m=12){
    $time=array();
    for ($i=0; $i <$m ; $i++) { 
        $time[]=date("Y-m", strtotime("-$i month"));
    }
    return $time;
}
/**
 * 获取上周指定日期时间
 * @param  $str 指定时间
 * @return unknown 时间
 */
function  get_lastweek_name($str){
  switch ($str) {
        case '1':
            $time = date("Y-m-d",mktime(0,0,0,date('m'),date('d')-1,date('Y')));
            break;
        case '2':
            $time = date("Y-m-d",mktime(0,0,0,date('m'),date('d')-2,date('Y')));
            break;
         case '3':
            $time = date("Y-m-d",mktime(0,0,0,date('m'),date('d')-3,date('Y')));
            break;
         case '4':
              $time = date("Y-m-d",mktime(0,0,0,date('m'),date('d')-4,date('Y')));
            break;
         case '5':
            $time = date("Y-m-d",mktime(0,0,0,date('m'),date('d')-5,date('Y')));
            break;
        case '6':
            $time = date("Y-m-d",mktime(0,0,0,date('m'),date('d')-6,date('Y')));
            break;
        case '7':
            $time = date("Y-m-d",mktime(0,0,0,date('m'),date('d')-7,date('Y')));
            break;
        default:
            $time =date("Y-m-d",mktime(0,0,0,date('m'),date('d'),date('Y')));
            break;

    }
    return $time;
}

//获取广告图类型
function get_adv_type($type=0){
    switch ($type) {
        case 1:
            return '单图';
            break;
        case 2:
            return '多图';
            break;
        case 3:
            return '文字链接';
            break;
        case 4:
            return '代码';
            break;
        default:
            return '未知类型';
            break;
    }
}

/**
 *获取广告位标题
 *@param int $pos_id
 *@return string
 *@author 小纯洁
 */
function get_adv_pos_title($pos_id=0){
    $adv_pos = M('AdvPos',"tab_");
    $map['id'] = $pos_id;
    $data = $adv_pos->where($map)->find();
    if(empty($data)){return "没有广告位";}
    return $data['title'];
}
function get_relation_game($id,$relation_id){
    if($id==$relation_id){
        $gdata=M('Game','tab_')->where(array('relation_game_id'=>$relation_id,'id'=>array('neq',$id)))->find();
        if(!$gdata){
            return false;//未关联游戏  即没有例外一个版本
        }else{
            return true;
        }
    }else{
        //再次确认关联的游戏
        $gdata=M('Game','tab_')->where(array('relation_game_id'=>$relation_id,'id'=>$relation_id))->find();
        if($gdata){
            return true;
        }else{
            return -1;  //数据出错
        }
    }
}
function get_kuaijie($type=''){
    if($type==''){
        return false;
    }else{
        $data=M('Member')->field('kuaijie_value')->where(array('uid'=>UID))->find();
    }
    $data=$data['kuaijie_value'];
    if(empty($data)){
        $data='1,2,3,4,5,6,7,8,9,10';
    }
    $dataa=explode(',',$data);
    if($type==1){
        if($data==''){
            $dataa='';
        }else{
            $map['id']=array('in',$data);
            $dataa=M('Kuaijieicon')->where($map)->select();
        }
    }elseif($type==2){
        if($data==''){
            $dataa=M('Kuaijieicon')->select();
        }else{
            $map['id']=array('not in',$data);
            $dataa=M('Kuaijieicon')->where($map)->select();
        }
    }
    foreach ($dataa as $key => $value) {
        foreach ($data as $k => $v) {
            if($value==$v['value']){
                $dataa[$key]=$v;
            }
        }
    }
    return $dataa;
}

/**
 * 获取游戏版本
 * @param $game_id
 * @return string
 */
function get_sdk_version($game_id){
    $game = M('Game','tab_')->find($game_id);
    $version = empty($game) ? '' : $game['sdk_version'];
    return $version;
}

    /**
     * [获取客服问题列表]
     * @return mixed
     */
function get_kefu_data(){
    $map['status']=1;
    $map['istitle']=1;
    $list = M('Kefuquestion','tab_')
      ->where($map)
      ->group('title')
      ->select();
    return $list;
}

/**
 * 渠道列表
 * @param $type
 * @return mixed
 */
function promote_lists($type){
    if($type == 1){
        $map['parent_id'] = 0;
    }elseif($type == 2){
        $map['parent_id'] = ['neq',0];
    }else{
        $map = '';
    }
    $data = M('promote','tab_')->where($map)->select();
    return $data;
}

//获取短消息未读条数
function get_msg($id = 0){
    $id = $id ? $id : session('user_auth.uid');
    $map['user_id'] = $id;
    $map['status'] = 2;
    $count = M('msg', 'tab_')->where($map)->count();
    return $count;
}

    /**
     * [去除数组汇总状态不为1的键]
     * @param $status
     * @param $param
     * @param array $array
     * @return array
     */
function array_status2value($status,$param,$array=array()){
    foreach ($array as $key => $value) {
        if($value[$status]!=1){
            unset($array[$key]);
        }
    }
    return $array;
}

/**
 * 获取渠道平台币
 * @param $promote_id
 * @return mixed
 */
function get_promote_coin($promote_id){
    $promote = M('promote','tab_')->find($promote_id);
    return $promote['balance_coin'];
}

/**
 * 获取渠道父类
 * @param $promote_id
 * @param string $field
 * @return mixed
 */
function get_promote_parent($promote_id,$field='account'){
    $Promote = M('promote','tab_');
    $data = $Promote->find($promote_id);
    if($data['parent_id'] != 0){
        $data = $Promote->find($data['parent_id']);
    }
    return $data[$field];
}
//区分渠道联盟站点
function promote_union($is_union,$id=''){
    if($id){
        $data=M('User','tab_')->where(array('id'=>$id))->find();
        $is_union=$data['is_union'];
    }
    if($is_union==0){
        return "推广员";
    }else{
        return "一牛联盟";
    }
}

//获取状态文字
function apply_domain_type($apply_domain_type){
    switch ($apply_domain_type) {
        case '0':
            return '系统分配';
            break;
        case '1':
            return '自主添加';
            break;
        default:
            return '参数错误';
            break;
    }
}
//根据用户id 或者渠道id 区分联盟站点
function get_union_type($id='',$tab){
    if($tab==''||$id==''){
        return false;
    }
    $data=M($tab,'tab_')->where(array('id'=>$id))->find();
    if($data['is_union']==0){
        return "推广员";
    }else{
        return "一牛联盟";
    }
}
//获取联盟站的所有用户id
function get_union_member($is_union=0,$tab='User'){
    $model=M($tab,'tab_')->field('id')->where(array('is_union'=>$is_union))->select();
    if($model){
        $data=array_column($model,'id');
    }else{
        $data=false;
    }
    return $data;
}
function app_version($type=0){
    switch ($type) {
        case '1':
            return 'Android';
            break;
        case '0':
            return 'IOS';
            break;
    }
}
function mdate($time = NULL) {
    $text = '';
    $time = $time === NULL || $time > time() ? time() : intval($time);
    $t = time() - $time; //时间差 （秒）
    $y = date('Y', $time)-date('Y', time());//是否跨年
    switch($t){
        case $t == 0:
            $text = '刚刚';
            break;
        case $t < 60:
            $text = $t . '秒前'; // 一分钟内
            break;
        case $t < 60 * 60:
            $text = floor($t / 60) . '分钟前'; //一小时内
            break;
        case $t < 60 * 60 * 24:
            $text = floor($t / (60 * 60)) . '小时前'; // 一天内
            break;
        case $t < 60 * 60 * 24 * 1:
            $text = '昨天 ' . date('H:i', $time);
            break;
        case $t < 60 * 60 * 24 * 30:
            $text = date('m-d H:i', $time); //一个月内
            break;
        case $t < 60 * 60 * 24 * 365&&$y==0:
            $text = date('m-d', $time); //一年内
            break;
        default:
            $text = date('Y-m-d-', $time); //一年以前
            break;
    }

    return $text;
}

/**
 * 验证退款记录
 * @param $pay_order_number
 * @return int
 */
function ischeck_refund($pay_order_number){
    $map['pay_order_number']=$pay_order_number;
    $find=M('refund_record','tab_')->where($map)->find();
    if(null==$find){
        return 1;
    }elseif($find['tui_status']=='2'){
        return 2;
    }elseif($find['tui_status']=='1'){
        return 0;
    }
}
/**
 * 获取支付方式
 * @param $order
 * @return mixed
 */
function get_spend_pay_way($order){
    $map['pay_order_number']=$order;
    $find=M('refund_record','tab_')->where($map)->find();
    return $find['pay_way'];
}

//获取渠道流水  不包括平台币
function promote_user_pay($pid,$gid=0,$isptb=0){
    if(empty($pid)){
        return false;
    }
    $map['promote_id'] = $pid;
    $map['is_check'] = 1;
    $map['pay_status'] = 1;
    $data = M('Spend','tab_')
            ->field("sum(pay_amount) as total")
            ->where($map)
            ->find();
    return $data['total']==null?'0.00':$data['total'];
}
/**
 * 获取积分类型列表
 * @return mixed
 * author: xmy 280564871@qq.com
 */
function get_point_type_lists(){
    $data =M("point_type","tab_")->where(['status'=>1])->select();
    return $data;
}

/**
 * 获取积分商品列表
 * author: xmy 280564871@qq.com
 */
function get_point_good_lists(){
    $data =M("point_shop","tab_")->where(['status'=>1])->select();
    return $data;
}

//获取前7天
function every_day($m=7){
    $time=array();
    for ($i=$m-1; $i >=0 ; $i--) {
        $time[]=date('Y-m-d',mktime(0,0,0,date('m'),date('d')-$i,date('Y')));
    }
    return $time;
}
// 两个日期之间的所有日期
function prDates($start,$end){
    $dt_start = strtotime($start);
    $dt_end = strtotime($end);
    while ($dt_start<=$dt_end){
        $tt[]=date('Y-m-d',$dt_start);
        $dt_start = strtotime('+1 day',$dt_start);
    }
    return $tt;
}
/* 获取色系  鹿文学 2017-11-17 */ 
function get_color_style_list() {
  $result = M('config')->field('extra,value')->find(13);
  
  if ($result) {
    
    $list['list'] = parse_config_attr($result['extra']);
  
    $list['value']=$result['value'];
  }
  return $list;
}

//获取某个表的一个字段的值
function get_table_oneparam($id,$table='',$param='game_id'){
    if(!$table){
        $table=M('UserCoin','tab_');
    }
    $data = $table->field($param)->find($id);
    return $data[$param];
}