<?php
namespace Mobile\Widget;
use Think\Controller;
use Common\Model\GameModel;
use Common\Model\GiftbagModel;
use Admin\Model\PointTypeModel;
use Common\Model\DocumentModel;
use Common\Api\GameApi;
class GameWidget extends Controller {

	public function suspension() {
        $this->show(is_login());
		$this->display('Game/suspension');
	}
    public function show($user){
        empty($user)&&$this->ajaxReturn(array('code'=>0,'msg'=>'未登录'));
        $user_data = M("user", "tab_")->field('nickname,shop_point')->find($user);
        $pointtype = new PointTypeModel();
        $lgmap['pt.key'] = 'sign_in';
        $lgjoin .= ' and pr.user_id = '.is_login();
        $loginpont = $pointtype->getUserLists($lgmap,$lgjoin,'ctime desc',1,1);
        if(empty($loginpont[0]['user_id'])){
            $issignin = 0;//今日是否签到
        }elseif(!empty($loginpont[0]['user_id'])&&$loginpont[0]['ct']==date('Y-m-d',time())){
            $issignin = 1;
        }else{
            $issignin = 0;
        }
        $yisign = strtotime($loginpont[0]['ct'])+24*3600;
        $nowsign = strtotime(date('Y-m-d',time()));
        if($yisign<$nowsign){
            $zhongduan =  1;
        }
        if($loginpont[0]['day']>=7||$loginpont[0]['day']<=0||$zhongduan==1||empty($loginpont[0]['day'])){
            $signday = 1;
        }else{
            $signday = $loginpont[0]['day']+1;
        }

        $addpoint = $loginpont[0]['point']+($signday-1)*$loginpont[0]['time_of_day'];//增加多少，今日签过 改变量不使用
        $this->assign('issignin',$issignin);
        $this->assign('addpoint',$addpoint);//今日
        $this->assign('userinfo',$user_data);
    }
}
