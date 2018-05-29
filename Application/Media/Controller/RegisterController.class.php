<?php
namespace Media\Controller;
use Think\Controller;
use User\Api\MemberApi;
use Common\Api\GameApi;
use User\Api\UserApi;
/**
* 首页
*/
class RegisterController extends BaseController {
/**
	* 登陆
	*/
	public function register($username='',$password='') {
		$up = D('User');

		if (IS_POST) {
			if (!preg_match("/^1[358][0-9]{9}$/",$username)) {
				echo json_encode(array('info'=>'请输入正确的手机号码','status'=>0));exit;
			}
			$User = new UserApi;
			$user = $up->checkUsername($username);
			if ($user && is_array($user)) {
				$uid = $up->checkPwd($username,$password);
				if (0<$uid) {
					$bool = $up->login($uid);
					if ($bool) {
						$data['info']="登陆成功";
						$data['status']=1;
						$data['url']= U("Game/open_game",array('game_id'=>$_REQUEST['game_id'],'pid'=>$_REQUEST['pid']));
					} else {
						$data['info']=$up->getError();
						$data['status']=0;
					}
				} else {
					switch($uid) {
						case -1: $error = '用户不存在！'; break; 
						case -2: $error = '密码错误！'; break;
						default: $error = '未知错误！'; break;
					}
					$data['info']=$error;
					$data['status']=0;
				}
			} else {
				$uid = $up->register($username,$password,$_REQUEST['pid']);
				if (0<$uid) {
					$bool = $up->login($uid);
					if ($bool) {
						$data['info']="登陆成功";
						$data['status']=1;
						$data['url']= U("Game/open_game",array('game_id'=>$_REQUEST['game_id'],'pid'=>$_REQUEST['pid']));
					} else {
						$data['info']=$up->getError();
						$data['status']=0;
					}
				} else {
					$n = $up->getError();
					$data['info']  = $this->getE($n);
					$data['status'] =  $n;
				}
			}
			echo json_encode($data);
		} else {
			header("Content-type: text/html; charset=utf-8"); 
			$url=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
			$map['url']= "http://".$url;
			$eteriorchan=M("eteriorchan","tab_")->where($map)->find();
			if($eteriorchan['state']==0){
				echo "该链接审核中....";
				exit();
			}else if($eteriorchan['state']==2){
				echo "该链接被驳回....";
				exit();
			}
			$user = $up->isLogin();
			if (!$user) {
				$flag = checkmobile();
				$this->assign('flag',!$flag);				
				$this->display();			
			} else {
				$this->redirect('Subscriber/index');
			}
		}
	}

    
    
}