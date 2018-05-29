<?php
namespace Org\UcenterSDK;
use Think\Exception;
include './uc_client/client.php';

class Ucservice  {

  //会员注册 //type 1 sdk 2 pc端
    public function uc_register($username, $password,$email="",$promote_id=0,$promote_account="自然注册",$game_id=0,$game_name="",$version,$type=2){
        $uid = uc_user_register($username, $password,$email,$promote_id,$promote_account,$game_id,$game_name,$version);//UCenter的注册验证函数
        if($type==1){
             return $uid;
        }else{
        if($uid <= 0) {
            if($uid == -1) {
              $data = array('status'=>0,'msg'=>'用户名不合法');
            } elseif($uid == -2) {
              $data = array('status'=>0,'msg'=>'包含不允许注册的词语');
            } elseif($uid == -3) {
              $data = array('status'=>0,'msg'=>'用户名已经存在');
            } elseif($uid == -4) {
              $data = array('status'=>0,'msg'=>'Email 格式有误');
            } elseif($uid == -5) {
              $data = array('status'=>0,'msg'=>'Email 不允许注册');
            } elseif($uid == -6) {
              $data = array('status'=>0,'msg'=>'Email 该 Email 已经被注册');
            } else {
              $data = array('status'=>0,'msg'=>'未定义');
            }
            return $this->ajaxReturn($data);
        } else {
            return intval($uid);//返回一个非负数
        }
        }

    }

    //uc记录充值数据
    public function uc_recharge($uid,$uaccount,$unick,$gid,$gappid,$gname,$sid,$sname,$pid,$paccount,$orderNo,$payOrderNo,$payAmount,$payTime,$extend,$payWay,$spendIp,$sdkVer,$version,$platform,$pay_notify_url,$game_key){
      $id=uc_add_spend($uid,$uaccount,$unick,$gid,$gappid,$gname,$sid,$sname,$pid,$paccount,$orderNo,$payOrderNo,$payAmount,$payTime,$extend,$payWay,$spendIp,$sdkVer,$version,$platform,$pay_notify_url,$game_key);
      return $id;
    }
    //uc记录充值状态
    public function uc_rechange_notify($payOrderNo,$payStatus,$payGameStatus){
      $id=uc_edit_spend($payOrderNo,$payStatus,$payGameStatus);
      return $id;
    }
    public function uc_spend_find($payOrderNo){
      $data=uc_find_spend($payOrderNo);
      return $data;
    }
    public function uc_spend_change($payOrderNo,$status){
      $result=uc_change_spend($payOrderNo,$status);
      return $result;
    }
    public function uc_recharge_select($page=1,$ppp=10,$param){
      $data=uc_select_spend($page,$ppp,$param);
      return $data;
    }
    public function get_user_from_username($username){
      $result=uc_get_user_by_username($username);
      return $result;
    }
    public function get_user_from_uid($uid){
      $result=uc_get_user_by_uid($uid);
      return $result;
    }

     // 会员登录 //type 1 sdk 2 pc端
    public function uc_login($username, $password,$type=2){

        list($uid, $username, $password, $email) = uc_user_login($username, $password);
        if($type==1){
          return $uid;
        }else{
        if($uid > 0) {
            return array(
            'uid' => $uid,
            'username' => $username,
            'password' => $password,
            'email' => $email
            );

        } elseif($uid == -1) {
           echo json_encode(array('status'=>0,'msg'=>'用户不存在,或者被删除'));
        } elseif($uid == -2) {
              echo json_encode(array('status'=>0,'msg'=>'密码错误')) ;
        } elseif($uid == -3) {
              echo json_encode(array('status'=>0,'msg'=>'安全问题错误')) ;
        } else {
             echo json_encode(array('status'=>0,'msg'=>'未定义')) ;
        }
        }

    }

    //编辑uc用户信息
    public function uc_edit($username,$oldpassword,$newpassword,$emailnew="",$ignoreoldp=0){
      $ucresult = uc_user_edit($username, $oldpassword, $newpassword,$emailnew,$ignoreoldp);
      return $ucresult;;
    }

    //判断uc用户是否存在
    public function get_uc($username){
      if($data = uc_get_user($username)) {
        list($uid, $username, $email) = $data;
        return $data;
      } else {
        return false;
      }
    }

    //删除uc用户信息
    public function uc_delete($uid){
        $d=uc_user_delete($uid);
        return $d;
    }


    public function uc_synlogin($uid){
        echo uc_user_synlogin($uid);

    }

   // 会员退出
   public function uc_user_logout(){

    setcookie('Example_auth', '', -86400);

        //生成同步退出的代码

        $ucsynlogout = uc_user_synlogout();

        return $ucsynlogout;

   }
   
}