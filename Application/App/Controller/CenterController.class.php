<?php

namespace App\Controller;
use Common\Model\PointTypeModel;
use Home\Controller\EmptyController;
use User\Api\MemberApi;
use Common\Model\GiftbagModel;
use Common\Model\GameModel;
use Common\Model\UserPlayModel;
use Common\Model\MsgModel;

class CenterController extends BaseController{
    public function appLogo(){
        $logourl = get_cover(C('WAP_SET_LOGO'),'path');
        if($logourl){
            $data['WAP_SET_LOGO'] = 'http://'.$_SERVER['HTTP_HOST'].get_cover(C('WAP_SET_LOGO'),'path');
        }else{
            $data['WAP_SET_LOGO']='';
        }
        $this->set_message(200,'success',$data);
    }

    /**
     * 个人中心首页
     */
    public function user($token){
        $this->auth($token);

        $pointtype = new PointTypeModel();
        $bindmap['pt.key'] = 'bind_phone';
        $bindjoin .= ' and pr.user_id = '.USER_ID;
        $bind = $pointtype->getUserLists($bindmap,$bindjoin,'ctime desc',1,1);
        $user_data = M("user", "tab_")->field('id,account,nickname,register_way,phone,shop_point,balance,age_status,head_icon')->find(USER_ID);
        if (!empty($user_data['phone'])){
            $userbind = 1;
        }else{
            $userbind = 0;
        }

        $lgmap['pt.key'] = 'sign_in';
        if(USER_ID){
            $lgjoin .= ' and pr.user_id = '.USER_ID;
        }else{
            $lgjoin .= ' and pr.user_id = '.USER_ID;
        }
        $loginpont = $pointtype->getUserLists($lgmap,$lgjoin,'ctime desc',1,1);
        
        if(empty($loginpont[0]['user_id'])){
            $issignin = 0;//今日是否签到
        }elseif(!empty($loginpont[0]['user_id'])&&$loginpont[0]['ct']==date('Y-m-d',time())){
            $issignin = 1;
        }else{
            $issignin = 0;
            if($loginpont[0]['ct']!=date("Y-m-d",strtotime("-1 day"))){
                $loginpont[0]['day'] = 0;
            }
        }
        if($loginpont[0]['day']>=7||$loginpont[0]['day']<=0||empty($loginpont[0]['day'])){
            $signday = 1;
            $tosignday = $signday+1;
        }else{
            $signday = $loginpont[0]['day']+1;
            $tosignday = $signday;
        }
        if($tosignday>=7){
            $tosignday = 1;
        }else{
            $tosignday = $tosignday;
        }
        
        $addpoint = $loginpont[0]['point']+($signday-1)*$loginpont[0]['time_of_day'];

        $data['age_status']=  $user_data['age_status'];
        $data['balance'] = $user_data['balance'];
        $data['shop_point'] = $user_data['shop_point'];
        $data['bind_point'] = $bind[0]['point'];
        $data['userbind'] = $userbind;
        $data['issignin'] = $issignin;
        $data['sign_point'] = $addpoint;
        $data['phone'] = $user_data['phone'] ? $user_data['phone'] : "";
        $data['uid'] = $user_data['id'];
        $data['head_icon'] = $user_data['head_icon'];
        $data['account'] = $user_data['account'];
        $data['PC_SET_SERVER_QQ'] = C('APP_QQ');
        
        $this->set_message(200,'success',$data);
    }


    /**
     * 实名认证
     */
    public function user_auth_data($token){
        $this->auth($token);
        $data = M('User','tab_')->where(array('id'=>USER_ID))->field('id,real_name,idcard,age_status')->find();

        $this->set_message(200,'success',$data);
    }

    public function auth_status(){
        $this->set_message(200,'success',get_tool_status('age')==1?1:0);
    }

    public function user_auth($token,$real_name,$idcard){
        $this->auth($token);

        //获取数据库里面的数据 如果不为空 则不进行实名认证
        $user = M('User','tab_')->where(array('id'=>USER_ID))->field('id,real_name,idcard')->find();
        if (!empty($user['real_name']) && !empty($user['idcard'])){
            $this->set_message(1122,'该用户已经填写过身份数据','');
        }else{

            if(isset($real_name)){
                if(!$this->isChineseName($real_name)){
                    $this->set_message(1110,'姓名不符合规范','');
                }
            }
            if($idcard){
                $checkidcard = new \Think\Checkidcard();
                $invidcard=$checkidcard->checkIdentity($idcard);
                if(!$invidcard){
                    $this->set_message(1123,'身份证号码填写不正确,如有字母请小写','');
                }
                $cardd=M('User','tab_')->where(array('idcard'=>$idcard))->find();
                if($cardd){
                    $this->set_message(1087,'身份证号码已被使用','');
                }
            }

            if (get_tool_status('age') == 0){
                $birthday = substr($idcard,6,4)."-".substr($idcard,10,2)."-".substr($idcard,12,2);
                $age = floor((time()-strtotime($birthday))/(60*60*24*365));
                if ($age>17){
                    $map['age_status'] = 2;
                }else{
                    $map['age_status'] = 3;
                }
            }else{
                $re = ageVerify($idcard,$real_name);
                switch ($re)
                {
                    case 0:
                        $this->set_message(1124,'认证失败 请稍后再试','');
                        break;
                    case 1://成年
                        $map['age_status'] = 2;
                        break;
                    case 2://未成年
                        $map['age_status'] = 3;
                        break;
                    default:
                }
            }
        }
        $map['id'] = USER_ID;
        $map['idcard'] = $idcard;
        $map['real_name'] = $real_name;
        $flag = M('User','tab_')->save($map);
        if ($flag!==false) {
            $this->set_message(200,'success','');
        }else{
            $this->set_message(1091,'实名认证失败','');
        }
    }

    function isChineseName($name){
        if(preg_match("/^([\xe4-\xe9][\x80-\xbf]{2}){2,4}$/",$name)){
            return true;
        }else {
            return false;
        }
    }

    /**
     * 绑定手机 （绑定成功之后需要给玩家加上积分）
     */
    public function user_bind_phone($token,$phone,$code){

        $this->auth($token);
        $this->check_code_return($phone,$code,$status=1,$type=2);

        $data['id'] = USER_ID;
        $data['phone'] = $phone;
        $user = new MemberApi();
        $result = $user->updateInfo($data);

        $pointtype = new PointTypeModel();
        $pointtype->startTrans();
        if ($result !== false) {
            $bindaddpoint = $pointtype->userGetPoint(USER_ID,'bind_phone');
            if($bindaddpoint==1){
                $pointtype->commit();
                $detail = $pointtype->alias('pt')->where(array('key'=>'bind_phone'))->find();
                $this->set_message(200,'success',$detail['point']);
            }elseif($bindaddpoint==-100){//不是首绑
                $pointtype->commit();
                $this->set_message(200,'success',-1);
            }else{
                $pointtype->rollback();
                $this->set_message(1104,'绑定失败',[]);
            }
        } else {
            $pointtype->rollback();
            $this->set_message(1104,'绑定失败',[]);
        }
    }

    public function user_unbind_phone($token,$phone,$code){
        $this->auth($token);
        $map['id'] = USER_ID;
        $map['phone'] = $phone;
        $yanzheng = M('User','tab_')->where($map)->find();
        if (!$yanzheng){
            $this->set_message(1099,'该手机号不存在','');
        }

        $this->check_code_return($phone,$code);

        $data['id'] = USER_ID;
        $data['phone'] = '';
        $user = new MemberApi();
        $result = $user->updateInfo($data);
        if ($result){
            $this->set_message(200,'success','');
        }else{
            $this->set_message(1105,'解绑失败','');
        }
    }


    public function check_code_return($phone,$v_code,$status=1,$type=''){
        $telcode = session($phone);
        if(!$telcode){
            $this->set_message(1100,'验证码无效，请重新获取验证码',$type==2?0:"");
        }

        $time = (time() - $telcode['create_time'])/60;
        if ($time>$telcode['delay']) {
            session('telsvcode',null);unset($telcode);
            $this->set_message(1102,'时间超时,请重新获取验证码',$type==2?0:"");
        }
        if ($telcode['code'] == $v_code) {
            if ($status==1){
                session('telsvcode',null);unset($telcode);
                return true;
            }else{
                $this->set_message(200,'success',$type==2?0:"");
            }
        }else{
            $this->set_message(1103,'验证码不正确，请重新输入',$type==2?0:"");
        }
    }

    /**
     * 修改密码
     */
    public function user_password($token,$oldpwd,$newpwd,$nnewpwd){
        $this->auth($token);

        if($newpwd != $nnewpwd){
            $this->set_message(1085,'新密码和确认密码不一致',"");
        }else if(strlen($newpwd)>15||strlen($newpwd)<6){
            $this->set_message(1092,'新密码长度在6~15个字符',"");
        }elseif(!preg_match('/^[a-zA-Z0-9_\.]+$/',$newpwd)){
            $this->set_message(1093,'新密码由字母或数字组成',"");
        }elseif($oldpwd == $newpwd){
            $this->set_message(1094,'新密码和原密码不能一致',"");
        }
        $data['id']= USER_ID;
        $data['old_password'] = $oldpwd;
        $data['password'] = $newpwd;
        $user = new MemberApi();
        $result = $user->updateUser($data);
        if($result==-2){
            $this->set_message(1005,'原密码错误',"");
        }elseif($result !== false){
            $this->set_message(200,'success','');
        }else{
            $this->set_message(1012,'修改失败',"");
        }

    }

    /**
     * 忘记密码
     */
    public function forget_password($phone,$code,$newpwd,$nnewpwd){
        $this->check_code_return($phone,$code);
        if($newpwd != $nnewpwd){
            $this->set_message(1085,'新密码和确认密码不一致','');
        }else if(strlen($newpwd)>20||strlen($newpwd)<6){
            $this->set_message(1092,'新密码长度在6~20个字符','');
        }elseif(!preg_match('/^[a-zA-Z0-9_\.]+$/',$newpwd)){
            $this->set_message(1093,'新密码由字母或数字组成','');
        }

        $map['phone'] = $phone;
        $user = M('User','tab_')->where($map)->field('id,phone')->find();
        if (empty($user)){
            $this->set_message(1039,"该手机未绑定账号",'');
        }

        $me = new MemberApi();
        $result  =$me->updatePassword($user['id'],$newpwd);
        if ($result){
            $this->set_message(200,'success',array());
        }else{
            $this->set_message(1012,"修改失败",array());
        }
    }


    /**
     * 用户地址
     */
    public function user_address($token){
        $this->auth($token);

        $userinfo = M("user", "tab_")->field('id,shop_address')->find(USER_ID);

        $shop_address_info = json_decode($userinfo['shop_address'],true);
        if(!empty($shop_address_info)){
            $shop_address= explode('-',$shop_address_info['consignee_address']);
            $shop_address_info['consignee_address'] = $shop_address[0];
            unset($shop_address[0]);
            $detail= implode('',$shop_address);
            $shop_address_info['detailed_address'] = $detail;
        }
        if (!empty($shop_address_info)){
            $this->set_message(200,'success',!empty($shop_address_info)?$shop_address_info:[],1);
        }else{
            $this->set_message(1111,'用户地址不存在');
        }
    }

    /**
     * 添加地址
     */
    public function user_address_add($token,$name,$phone,$address,$detail){
        $this->auth($token);
        $userinfo = M("user", "tab_")->field('id,shop_address')->find(USER_ID);
        $shop_address_info = json_decode($userinfo['shop_address'],true);
        if ($shop_address_info){
            $this->set_message(1095,'收货地址已存在','');
        }

				if (mb_strlen($name)<2) {$this->set_message(1124,'请输入正确的收获人姓名','');}
				if (!preg_match('/^(([a-zA-Z]+)|([\x7f-\xff]+))$/',$name)) {$this->set_message(1124,'请输入正确的收获人姓名','');}
				
				if (strlen($phone)<7) {$this->set_message(1125,'请输入正确的联系电话','');}
				if (!preg_match('/^(?:(\d{3,4}-\d{8})|(1\d{10}))$/',$phone)) {$this->set_message(1125,'请输入正确的联系电话','');}
				
				if ($address == '' || '省份 城市 区县'==$address) {$this->set_message(1126,'请选择所在区域','');}
				
				if (mb_strlen($detail)<5 || is_numeric($detail)) {$this->set_message(1127,'请输入正确的详细地址','');}
				
				
        $data['consignee'] = $name;
        $data['consignee_phone'] = $phone;
        $data['consignee_address'] = $address.'-'.$detail;

        $save['id'] = USER_ID;
        $save['shop_address'] = json_encode($data);

        $result = M('User','tab_')->save($save);
        if ($result){
            $this->set_message(200,'success','');
        }else{
            $this->set_message(1096,'保存失败','');
        }
    }

    /**
     * 编辑地址
     */
    public function user_address_edit($token,$name,$phone,$address,$detail){
        $this->auth($token);
        $userinfo = M("user", "tab_")->field('id,shop_address')->find(USER_ID);
        $shop_address_info = json_decode($userinfo['shop_address'],true);
        if (!$shop_address_info){
            $this->set_message(1097,'不存在可以编辑的收货地址','');
        }
				
				if (mb_strlen($name)<2) {$this->set_message(1124,'请输入正确的收获人姓名','');}
				if (!preg_match('/^(([a-zA-Z]+)|([\x7f-\xff]+))$/',$name)) {$this->set_message(1124,'请输入正确的收获人姓名','');}
				
				if (strlen($phone)<7) {$this->set_message(1125,'请输入正确的联系电话','');}
				if (!preg_match('/^(?:(\d{3,4}-\d{8})|(1\d{10}))$/',$phone)) {$this->set_message(1125,'请输入正确的联系电话','');}
				
				if ($address == '' || '省份 城市 区县'==$address) {$this->set_message(1126,'请选择所在区域','');}
				
				if (mb_strlen($detail)<5 || is_numeric($detail)) {$this->set_message(1127,'请输入正确的详细地址','');}
				

        $data['consignee'] = $name;
        $data['consignee_phone'] = $phone;
        $data['consignee_address'] = $address.'-'.$detail;

        $save['id'] = USER_ID;
        $save['shop_address'] = json_encode($data);

        $result = M('User','tab_')->save($save);
        if ($result!==false){
            $this->set_message(200,'success','');
        }else{
            $this->set_message(1096,'保存失败','');
        }
    }

    /**
     * 删除地址
     */
    public function user_address_del($token){
        $this->auth($token);

        $result = M('User','tab_')->where(array('id'=>USER_ID))->setField('shop_address','');
        if ($result !== false){
            $this->set_message(200,'success','');
        }else{
            $this->set_message(1051,'删除失败','');
        }
    }

    /**
     * 我的礼包
     */
    public function user_gift($token){
        $this->auth($token);
        $giftbgmodel = new GiftbagModel();
        $allgamegift = $giftbgmodel->myGiftRecord(USER_ID);
        if (empty($allgamegift)){
            $allgamegift = [];
        }
        $this->set_message(200,'success',$allgamegift);
    }
    public function user_no_gift(){
        $giftbgmodel = new GiftbagModel();
        $gamegift = $giftbgmodel->getGiftLists(array('gt',0),1,3,array('giftbag_type'=>1),'','gb.id desc');
        $this->set_message(200,'success',empty($gamegift)?[]:$gamegift);
    }
    /**
     * 我的收藏
     */
    public function user_collection($token,$p1=1,$p2=1,$type=1){
        $this->auth($token);
        $model = new GameModel();
        $user = USER_ID;
        $data1 = $model->myGameCollect($user,1,$p1);
        $data2 = $model->myGameFoot($user,2,$p2);

        $data3 = array();

        if ($type == 2){
            foreach ($data2 as $key => $value){
                $data3[$key]['data'] = $key;
                $data3[$key]['game_data'] = $data2[$key];
            }

            $data2 = array_values($data3);
        }else{
            foreach ($data2 as $key => $value){
                $data3[$key]['data'] = $key;
                $data3[$key]['game_data'] = $data2[$key];
            }

            $data2 = array_values($data3);
        }


        $data['collect'] = empty($data1)?[]:$data1;
        $data['foot'] = empty($data2)?[]:$data2;

        $this->set_message(200,'success',$data);
    }

    /**
     * 我的收藏（放到了ACTION里面）
     */
    public function user_collection_del(){

    }

    /**
     * 联系我们
     */
    public function user_contanct(){

    }

    /**
     * 余额
     */
    public function user_balance($token,$p=1){
        $this->auth($token);

        $model = new UserPlayModel();
        $bindmap['user_id'] = USER_ID;
        $bindmap['bind_balance'] = array('gt',0);
        $binddata = $model->getUserPlay($bindmap,$order="u.play_time desc",$p);

        $user_data = M("user", "tab_")->field('id,balance,shop_point')->find(USER_ID);

        $data['shop_point'] = $user_data['shop_point'];
        $data['balance'] = $user_data['balance'];
        $data['binddata'] = empty($binddata)?[]:$binddata;

        $this->set_message(200,'success',$data);
    }


    /**
     * 用户消息
     */
    public function user_message($token,$p=1,$type=0){
        $this->auth($token);

        $model = new MsgModel();
        $map['user_id'] = USER_ID;
        $map['status'] = array('gt',0);
        if($type == 1){
            $map1['user_id'] = USER_ID;
            $map1['status'] = 2;
            $model->optionMsg($map1);
        }
        $msg = $model->getMsglist($map,$order="create_time desc ,id desc",$p);

        if (empty($msg)){
            $msg = [];
        }
        $this->set_message(200,'',$msg);
    }


    public function user_contact($token="",$type=1){
        $data['PC_SET_SERVER_QQ'] = C('APP_QQ');
        $data['PC_COMMUNICATION_GROUP'] = C('APP_QQ_GROUP');
        $data['PC_SET_SERVER_TEL'] = C('APP_PHONE');
        $data['APP_COOPERATION'] = C('APP_COOPERATION');
        $data['PC_OFFICIAL_SITE'] = C('APP_NETWORK');
        $data['USER_AGREEMENT'] = "http://".$_SERVER['HTTP_HOST']."/mobile.php/Subscriber/user_argeement/type/1";
        $data['APP_VERSION'] = $type==1?C('APP_VERSION'):C('IOS_VERSION');
        $data['APP_NEW_VERSION'] = $type==1?C('APP_NEW_VERSION'):C('IOS_NEW_VERSION');
        $data['APP_VERSION_NAME'] = $type==1?C('APP_VERSION_NAME'):C('IOS_VERSION_NAME');
        $data['APP_DOWNLOAD'] = $type==1?C('APP_DOWNLOAD'):C('IOS_DOWNLOAD');
        $data['APP_COPYRIGHT'] = C('APP_COPYRIGHT');
        $data['APP_COPYRIGHT_EN'] = C('APP_COPYRIGHT_EN');
        if (!empty($token)){
            $this->auth($token);
            $pointtype = new PointTypeModel();
            $bindmap['pt.key'] = 'bind_phone';
            $bindjoin .= ' and pr.user_id = '.USER_ID;
            $bind = $pointtype->getUserLists($bindmap,$bindjoin,'ctime desc',1,1);
            $user_data = M("user", "tab_")->field('id,account,nickname,register_way,phone,shop_point,balance,age_status,head_icon')->find(USER_ID);
            if (!empty($user_data['phone'])){
                $userbind = 1;
            }else{
                $userbind = 0;
            }

            $lgmap['pt.key'] = 'sign_in';
            if(USER_ID){
                $lgjoin .= ' and pr.user_id = '.USER_ID;
            }else{
                $lgjoin .= ' and pr.user_id = '.USER_ID;
            }
            $loginpont = $pointtype->getUserLists($lgmap,$lgjoin,'ctime desc',1,1);
            if(empty($loginpont[0]['user_id'])){
                $issignin = 0;//今日是否签到
            }elseif(!empty($loginpont[0]['user_id'])&&$loginpont[0]['ct']==date('Y-m-d',time())){
                $issignin = 1;
            }else{
                $issignin = 0;
                if($loginpont[0]['ct']!=date("Y-m-d",strtotime("-1 day"))){
                    $loginpont[0]['day'] = 0;
                }
            }
            if($loginpont[0]['day']>=7||$loginpont[0]['day']<=0||empty($loginpont[0]['day'])){
                $signday = 1;
                $tosignday = $signday+1;
            }else{
                $signday = $loginpont[0]['day']+1;
                $tosignday = $signday;
            }
            if($tosignday>=7){
                $tosignday = 1;
            }else{
                $tosignday = $tosignday;
            }

            $addpoint = $loginpont[0]['point']+($signday-1)*$loginpont[0]['time_of_day'];

            $data['age_status']=  $user_data['age_status'];
            $data['balance'] = $user_data['balance'];
            $data['shop_point'] = $user_data['shop_point'];
            $data['bind_point'] = $bind[0]['point'];
            $data['userbind'] = $userbind;
            $data['issignin'] = $issignin;
            $data['sign_point'] = $addpoint;
            $data['phone'] = $user_data['phone'] ? $user_data['phone'] : "";
            $data['uid'] = $user_data['id'];
            $data['head_icon'] = $user_data['head_icon'];
            $data['account'] = $user_data['account'];

            $aa['user_id'] = USER_ID;
            $aa['status'] = 2;
            $model = new MsgModel();
            $msg = $model->getMsglist($aa,$order="create_time desc",1);
            $data['message_read'] = empty($msg)?2:1;
        }else{
            $pointtype = new PointTypeModel();
            $bindmap['pt.key'] = 'bind_phone';
            $bindjoin .= ' and pr.user_id = 0';
            $bind = $pointtype->getUserLists($bindmap,$bindjoin,'ctime desc',1,1);
            $data['bind_point'] = $bind[0]['point'];
        }
        $this->set_message(200,'success',$data);
    }

    /**
     * 开机图片
     */
    public function get_app_icon(){
        $data['start_icon'] = "http://".$_SERVER['HTTP_HOST'].get_cover(C('APP_SET_COVER'),'path');
        $data['logo'] = "http://".$_SERVER['HTTP_HOST'].get_cover(C('APP_LOGO'),'path');

        $this->set_message(200,'success',$data);
    }
}